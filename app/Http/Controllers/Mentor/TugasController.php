<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mentor\StoreTugasRequest;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    /**
     * Mentor yang sedang login.
     */
    protected function getMentor(): Mentor
    {
        $mentor = Mentor::query()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (! $mentor) {
            abort(403, 'Profil mentor belum tersedia.');
        }

        return $mentor;
    }

    /**
     * Query tugas yang hanya dimiliki mahasiswa bimbingan mentor.
     */
    protected function tugasQuery(Mentor $mentor)
    {
        return Tugas::query()
            ->with([
                'penempatan.mahasiswa.user',
                'penempatan.mentor.user',
                'penempatan.periodeMagang',
                'creator',
                'pengumpulan.mahasiswa.user',
                'pengumpulan.reviewer',
            ])
            ->whereHas('penempatan', function ($query) use ($mentor) {
                $query
                    ->where('mentor_id', $mentor->id)
                    ->where('status', 'active');
            });
    }

    /**
     * Daftar tugas mentor.
     */
    public function index(Request $request): View
    {
        $mentor = $this->getMentor();

        $status = $request->input('status');

        $tugas = $this->tugasQuery($mentor)
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->get();

        $rekap = [
            'total' => $tugas->count(),

            'draft' => $tugas
                ->where('status', 'draft')
                ->count(),

            'published' => $tugas
                ->where('status', 'published')
                ->count(),

            'closed' => $tugas
                ->where('status', 'closed')
                ->count(),
        ];

        return view('mentor.tugas.index', [
            'mentor' => $mentor,
            'tugas' => $tugas,
            'rekap' => $rekap,
            'statusFilter' => $status,
        ]);
    }

    /**
     * Form buat tugas.
     */
    public function create(): View
    {
        $mentor = $this->getMentor();

        $penempatans = Penempatan::query()
            ->with([
                'mahasiswa.user',
                'mentor.user',
                'periodeMagang',
            ])
            ->where('mentor_id', $mentor->id)
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
            ->get();

        return view('mentor.tugas.create', [
            'mentor' => $mentor,
            'penempatans' => $penempatans,
        ]);
    }

    /**
     * Simpan tugas sebagai draft.
     */
    public function store(
        StoreTugasRequest $request
    ): RedirectResponse {
        $mentor = $this->getMentor();

        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Penempatan harus benar-benar milik mentor yang sedang login.
        |--------------------------------------------------------------------------
        */
        $penempatan = Penempatan::query()
            ->with('periodeMagang')
            ->whereKey($validated['penempatan_id'])
            ->where('mentor_id', $mentor->id)
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
            ->first();

        if (! $penempatan) {
            return back()
                ->withErrors([
                    'penempatan_id' =>
                    'Mahasiswa yang dipilih bukan mahasiswa bimbingan Anda.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi tanggal terhadap periode magang.
        |--------------------------------------------------------------------------
        */
        $periodeMulai = Carbon::parse(
            $penempatan->periodeMagang->tanggal_mulai
        )->startOfDay();

        $periodeSelesai = Carbon::parse(
            $penempatan->periodeMagang->tanggal_selesai
        )->endOfDay();

        $tanggalMulai = Carbon::parse(
            $validated['tanggal_mulai']
        );

        $deadline = Carbon::parse(
            $validated['tanggal_deadline']
        );

        if (
            $tanggalMulai->lt($periodeMulai) ||
            $tanggalMulai->gt($periodeSelesai)
        ) {
            return back()
                ->withErrors([
                    'tanggal_mulai' =>
                    'Tanggal mulai tugas harus berada dalam periode magang.',
                ])
                ->withInput();
        }

        if (
            $deadline->lt($periodeMulai) ||
            $deadline->gt($periodeSelesai)
        ) {
            return back()
                ->withErrors([
                    'tanggal_deadline' =>
                    'Deadline tugas harus berada dalam periode magang.',
                ])
                ->withInput();
        }

        $path = null;

        if ($request->hasFile('file_tugas')) {
            $path = $request
                ->file('file_tugas')
                ->store('tugas/file', 'public');
        }

        DB::transaction(function () use (
            $validated,
            $path,
            $mentor
        ): void {
            Tugas::create([
                'penempatan_id' => $validated['penempatan_id'],
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_deadline' => $validated['tanggal_deadline'],
                'file_tugas' => $path,
                'status' => 'draft',

                /*
                |--------------------------------------------------------------------------
                | created_by = user mentor
                |--------------------------------------------------------------------------
                */
                'created_by' => $mentor->user_id,
            ]);
        });

        return redirect()
            ->route('mentor.tugas.index')
            ->with(
                'success',
                'Tugas berhasil dibuat sebagai draft.'
            );
    }

    /**
     * Detail tugas.
     */
    public function show(Tugas $tugas): View
    {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsTask(
            $mentor,
            $tugas
        );

        $tugas->load([
            'penempatan.mahasiswa.user',
            'penempatan.mentor.user',
            'penempatan.periodeMagang',
            'creator',
            'pengumpulan.mahasiswa.user',
            'pengumpulan.reviewer',
        ]);

        return view('mentor.tugas.show', [
            'mentor' => $mentor,
            'tugas' => $tugas,
        ]);
    }

    /**
     * Edit tugas draft.
     */
    public function edit(Tugas $tugas): View|RedirectResponse
    {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsTask(
            $mentor,
            $tugas
        );

        if ($tugas->status !== 'draft') {
            return redirect()
                ->route('mentor.tugas.show', $tugas)
                ->withErrors([
                    'tugas' =>
                    'Tugas yang sudah dipublikasikan atau ditutup tidak dapat diedit.',
                ]);
        }

        $penempatans = Penempatan::query()
            ->with([
                'mahasiswa.user',
                'mentor.user',
                'periodeMagang',
            ])
            ->where('mentor_id', $mentor->id)
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
            ->get();

        return view('mentor.tugas.edit', [
            'mentor' => $mentor,
            'tugas' => $tugas,
            'penempatans' => $penempatans,
        ]);
    }

    /**
     * Update tugas draft.
     */
    public function update(
        StoreTugasRequest $request,
        Tugas $tugas
    ): RedirectResponse {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsTask(
            $mentor,
            $tugas
        );

        if ($tugas->status !== 'draft') {
            return redirect()
                ->route('mentor.tugas.show', $tugas)
                ->withErrors([
                    'tugas' =>
                    'Tugas yang sudah dipublikasikan atau ditutup tidak dapat diedit.',
                ]);
        }

        $validated = $request->validated();

        $penempatan = Penempatan::query()
            ->with('periodeMagang')
            ->whereKey($validated['penempatan_id'])
            ->where('mentor_id', $mentor->id)
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
            ->first();

        if (! $penempatan) {
            return back()
                ->withErrors([
                    'penempatan_id' =>
                    'Mahasiswa yang dipilih bukan mahasiswa bimbingan Anda.',
                ])
                ->withInput();
        }

        $periodeMulai = Carbon::parse(
            $penempatan->periodeMagang->tanggal_mulai
        )->startOfDay();

        $periodeSelesai = Carbon::parse(
            $penempatan->periodeMagang->tanggal_selesai
        )->endOfDay();

        $tanggalMulai = Carbon::parse(
            $validated['tanggal_mulai']
        );

        $deadline = Carbon::parse(
            $validated['tanggal_deadline']
        );

        if (
            $tanggalMulai->lt($periodeMulai) ||
            $tanggalMulai->gt($periodeSelesai)
        ) {
            return back()
                ->withErrors([
                    'tanggal_mulai' =>
                    'Tanggal mulai tugas harus berada dalam periode magang.',
                ])
                ->withInput();
        }

        if (
            $deadline->lt($periodeMulai) ||
            $deadline->gt($periodeSelesai)
        ) {
            return back()
                ->withErrors([
                    'tanggal_deadline' =>
                    'Deadline tugas harus berada dalam periode magang.',
                ])
                ->withInput();
        }

        $path = $tugas->file_tugas;

        if ($request->hasFile('file_tugas')) {

            if ($path) {
                Storage::disk('public')->delete($path);
            }

            $path = $request
                ->file('file_tugas')
                ->store('tugas/file', 'public');
        }

        $tugas->update([
            'penempatan_id' => $validated['penempatan_id'],
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_deadline' => $validated['tanggal_deadline'],
            'file_tugas' => $path,
        ]);

        return redirect()
            ->route('mentor.tugas.show', $tugas)
            ->with(
                'success',
                'Tugas berhasil diperbarui.'
            );
    }

    /**
     * Publish tugas.
     */
    public function publish(Tugas $tugas): RedirectResponse
    {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsTask(
            $mentor,
            $tugas
        );

        if ($tugas->status !== 'draft') {
            return back()->withErrors([
                'tugas' =>
                'Hanya tugas draft yang dapat dipublikasikan.',
            ]);
        }

        if (
            Carbon::parse(
                $tugas->tanggal_deadline
            )->lt(now())
        ) {
            return back()->withErrors([
                'tugas' =>
                'Tugas dengan deadline yang sudah lewat tidak dapat dipublikasikan.',
            ]);
        }

        $tugas->update([
            'status' => 'published',
        ]);

        return redirect()
            ->route('mentor.tugas.show', $tugas)
            ->with(
                'success',
                'Tugas berhasil dipublikasikan.'
            );
    }

    /**
     * Tutup tugas.
     */
    public function close(Tugas $tugas): RedirectResponse
    {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsTask(
            $mentor,
            $tugas
        );

        if ($tugas->status !== 'published') {
            return back()->withErrors([
                'tugas' =>
                'Hanya tugas yang sedang dipublikasikan yang dapat ditutup.',
            ]);
        }

        $tugas->update([
            'status' => 'closed',
        ]);

        return redirect()
            ->route('mentor.tugas.show', $tugas)
            ->with(
                'success',
                'Tugas berhasil ditutup.'
            );
    }

    /**
     * Pastikan tugas adalah milik mahasiswa bimbingan mentor.
     */
    protected function ensureMentorOwnsTask(
        Mentor $mentor,
        Tugas $tugas
    ): void {
        $belongsToMentor = $tugas->penempatan()
            ->where('mentor_id', $mentor->id)
            ->where('status', 'active')
            ->exists();

        if (! $belongsToMentor) {
            abort(403);
        }
    }
}
