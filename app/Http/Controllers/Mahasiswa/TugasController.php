<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StorePengumpulanTugasRequest;
use App\Models\Logbook;
use App\Models\Penempatan;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    /**
     * Ambil penempatan aktif mahasiswa.
     */
    protected function getPenempatan(): ?Penempatan
    {
        $user = Auth::user();

        return Penempatan::query()
            ->with([
                'mahasiswa.user',
                'mentor.user',
                'periodeMagang',
            ])
            ->whereHas('mahasiswa', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'active')
            ->whereHas('mahasiswa', function ($query) {
                $query->where('status', 'active');
            })
            ->whereHas('mahasiswa.user', function ($query) {
                $query->where('status', 'active');
            })
            ->whereHas('periodeMagang', function ($query) {
                $query->where('status', 'active');
            })
            ->latest()
            ->first();
    }

    /**
     * Daftar tugas mahasiswa.
     */
    public function index(): View
    {
        $penempatan = $this->getPenempatan();

        $tugas = collect();

        if ($penempatan) {
            $tugas = Tugas::query()
                ->with([
                    'penempatan.mentor.user',
                    'penempatan.periodeMagang',
                    'pengumpulan.mahasiswa',
                ])
                ->where('penempatan_id', $penempatan->id)
                ->whereIn('status', ['published', 'closed'])
                ->orderByDesc('tanggal_deadline')
                ->get();
        }

        return view('mahasiswa.tugas.index', [
            'penempatan' => $penempatan,
            'tugas' => $tugas,
        ]);
    }

    /**
     * Detail tugas.
     */
    public function show(Tugas $tugas): View
    {
        $penempatan = $this->getPenempatan();

        if (
            ! $penempatan ||
            $tugas->penempatan_id !== $penempatan->id
        ) {
            abort(403);
        }

        if (! in_array(
            $tugas->status,
            ['published', 'closed'],
            true
        )) {
            abort(404);
        }

        $tugas->load([
            'penempatan.mahasiswa.user',
            'penempatan.mentor.user',
            'penempatan.periodeMagang',
            'creator',
        ]);

        $pengumpulan = PengumpulanTugas::query()
            ->where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $penempatan->mahasiswa_id)
            ->first();

        return view('mahasiswa.tugas.show', [
            'penempatan' => $penempatan,
            'tugas' => $tugas,
            'pengumpulan' => $pengumpulan,
        ]);
    }

    /**
     * Simpan/update draft jawaban.
     */
    public function store(
        StorePengumpulanTugasRequest $request,
        Tugas $tugas
    ): RedirectResponse {
        $penempatan = $this->getPenempatan();

        if (
            ! $penempatan ||
            $tugas->penempatan_id !== $penempatan->id
        ) {
            abort(403);
        }

        if ($tugas->status !== 'published') {
            return back()->withErrors([
                'tugas' =>
                'Tugas ini sudah ditutup atau belum tersedia untuk dikerjakan.',
            ]);
        }

        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | Belum mulai
        |--------------------------------------------------------------------------
        */
        if ($now->lt(
            Carbon::parse($tugas->tanggal_mulai)
        )) {
            return back()->withErrors([
                'tugas' =>
                'Tugas ini belum dapat dikerjakan karena belum memasuki waktu mulai.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Lewat deadline
        |--------------------------------------------------------------------------
        */
        if ($now->gt(
            Carbon::parse($tugas->tanggal_deadline)
        )) {
            return back()->withErrors([
                'tugas' =>
                'Deadline tugas sudah lewat.',
            ]);
        }

        $mahasiswaId = $penempatan->mahasiswa_id;

        $pengumpulan = PengumpulanTugas::query()
            ->where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Submitted/reviewed tidak boleh diedit.
        |--------------------------------------------------------------------------
        | Revision boleh diedit kembali.
        |--------------------------------------------------------------------------
        */
        if ($pengumpulan) {

            if (in_array(
                $pengumpulan->status,
                ['submitted', 'reviewed'],
                true
            )) {
                return back()->withErrors([
                    'tugas' =>
                    'Pengumpulan tugas yang sudah dikirim tidak dapat diedit.',
                ]);
            }

            if (
                ! in_array(
                    $pengumpulan->status,
                    ['draft', 'revision'],
                    true
                )
            ) {
                return back()->withErrors([
                    'tugas' =>
                    'Status pengumpulan tidak dapat diubah.',
                ]);
            }
        }

        $path = $pengumpulan?->file_jawaban;

        if ($request->hasFile('file_jawaban')) {

            if ($path) {
                Storage::disk('public')
                    ->delete($path);
            }

            $path = $request
                ->file('file_jawaban')
                ->store('tugas/jawaban', 'public');
        }

        if (! $pengumpulan) {

            PengumpulanTugas::create([
                'tugas_id' => $tugas->id,
                'mahasiswa_id' => $mahasiswaId,
                'jawaban' => $request->input('jawaban'),
                'file_jawaban' => $path,
                'status' => 'draft',
            ]);
        } else {

            $pengumpulan->update([
                'jawaban' => $request->input('jawaban'),
                'file_jawaban' => $path,
            ]);
        }

        return redirect()
            ->route('mahasiswa.tugas.show', $tugas)
            ->with(
                'success',
                'Jawaban berhasil disimpan sebagai draft.'
            );
    }

    /**
     * Submit jawaban.
     */
    public function submit(Tugas $tugas): RedirectResponse
    {
        $penempatan = $this->getPenempatan();

        if (
            ! $penempatan ||
            $tugas->penempatan_id !== $penempatan->id
        ) {
            abort(403);
        }

        if ($tugas->status !== 'published') {
            return back()->withErrors([
                'tugas' =>
                'Tugas ini tidak sedang dibuka untuk pengumpulan.',
            ]);
        }

        $now = Carbon::now();

        if ($now->lt(
            Carbon::parse($tugas->tanggal_mulai)
        )) {
            return back()->withErrors([
                'tugas' =>
                'Tugas belum dapat dikumpulkan karena belum memasuki waktu mulai.',
            ]);
        }

        if ($now->gt(
            Carbon::parse($tugas->tanggal_deadline)
        )) {
            return back()->withErrors([
                'tugas' =>
                'Deadline tugas sudah lewat.',
            ]);
        }

        $pengumpulan = PengumpulanTugas::query()
            ->where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $penempatan->mahasiswa_id)
            ->first();

        if (! $pengumpulan) {
            return back()->withErrors([
                'tugas' =>
                'Belum ada jawaban yang disimpan.',
            ]);
        }

        if (
            ! in_array(
                $pengumpulan->status,
                ['draft', 'revision'],
                true
            )
        ) {
            return back()->withErrors([
                'tugas' =>
                'Jawaban ini sudah dikirim dan tidak dapat dikirim kembali.',
            ]);
        }

        $hasJawaban = trim(
            (string) $pengumpulan->jawaban
        ) !== '';

        $hasFile = ! empty($pengumpulan->file_jawaban);

        if (! $hasJawaban && ! $hasFile) {
            return back()->withErrors([
                'tugas' =>
                'Isi jawaban atau unggah file sebelum mengirim tugas.',
            ]);
        }

        DB::transaction(function () use (
            $pengumpulan
        ): void {
            $pengumpulan->update([
                'status' => 'submitted',
                'dikumpulkan_at' => now(),
            ]);
        });

        return redirect()
            ->route('mahasiswa.tugas.show', $tugas)
            ->with(
                'success',
                'Tugas berhasil dikumpulkan dan menunggu pemeriksaan mentor.'
            );
    }
}
