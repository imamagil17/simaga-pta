<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StoreLogbookRequest;
use App\Models\Logbook;
use App\Models\Penempatan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LogbookController extends Controller
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
     * Validasi apakah tanggal logbook masih diperbolehkan.
     */
    protected function validateLogbookDate(
        Penempatan $penempatan,
        Carbon $tanggal
    ): ?string {
        /*
        |--------------------------------------------------------------------------
        | Hanya Senin - Jumat
        |--------------------------------------------------------------------------
        */
        if (! $tanggal->isWeekday()) {
            return 'Logbook hanya dapat dibuat pada hari kerja, yaitu Senin sampai Jumat.';
        }

        $periode = $penempatan->periodeMagang;

        $tanggalMulai = Carbon::parse(
            $periode->tanggal_mulai
        )->startOfDay();

        $tanggalSelesai = Carbon::parse(
            $periode->tanggal_selesai
        )->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | Sebelum periode
        |--------------------------------------------------------------------------
        */
        if ($tanggal->lt($tanggalMulai)) {
            return 'Periode magang Anda belum dimulai.';
        }

        /*
        |--------------------------------------------------------------------------
        | Setelah periode
        |--------------------------------------------------------------------------
        */
        if ($tanggal->gt($tanggalSelesai)) {
            return 'Periode magang Anda sudah berakhir.';
        }

        /*
        |--------------------------------------------------------------------------
        | Tidak boleh tanggal masa depan
        |--------------------------------------------------------------------------
        */
        if ($tanggal->gt(Carbon::today())) {
            return 'Logbook untuk tanggal yang akan datang tidak diperbolehkan.';
        }

        return null;
    }

    /**
     * Halaman utama logbook mahasiswa.
     */
    public function index(): View
    {
        $penempatan = $this->getPenempatan();

        $today = Carbon::today();

        $logbookHariIni = null;

        $hariKerja = false;

        $periodeBerjalan = false;

        $alasanTidakBisaMembuat = null;

        if ($penempatan) {
            $hariKerja = $today->isWeekday();

            $alasanTidakBisaMembuat = $this->validateLogbookDate(
                $penempatan,
                $today
            );

            $periodeBerjalan =
                $alasanTidakBisaMembuat === null;

            $logbookHariIni = Logbook::query()
                ->where('penempatan_id', $penempatan->id)
                ->whereDate('tanggal', $today)
                ->first();
        }

        $logbooks = $penempatan
            ? Logbook::query()
            ->where('penempatan_id', $penempatan->id)
            ->orderByDesc('tanggal')
            ->get()
            : collect();

        return view('mahasiswa.logbook.index', [
            'penempatan' => $penempatan,
            'logbookHariIni' => $logbookHariIni,
            'logbooks' => $logbooks,
            'hariKerja' => $hariKerja,
            'periodeBerjalan' => $periodeBerjalan,
            'alasanTidakBisaMembuat' => $alasanTidakBisaMembuat,
        ]);
    }

    /**
     * Form membuat logbook.
     */
    public function create(): View|RedirectResponse
    {
        $penempatan = $this->getPenempatan();

        if (! $penempatan) {
            abort(
                403,
                'Anda belum memiliki penempatan magang yang aktif.'
            );
        }

        $today = Carbon::today();

        $error = $this->validateLogbookDate(
            $penempatan,
            $today
        );

        if ($error) {
            abort(403, $error);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan hanya satu logbook per hari.
        |--------------------------------------------------------------------------
        */
        $existing = Logbook::query()
            ->where('penempatan_id', $penempatan->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existing) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->withErrors([
                    'logbook' => 'Logbook untuk hari ini sudah dibuat.',
                ]);
        }

        return view('mahasiswa.logbook.create', [
            'penempatan' => $penempatan,
            'tanggal' => $today,
        ]);
    }

    /**
     * Form edit logbook.
     *
     * Hanya draft dan revision yang boleh diedit.
     */
    public function edit(Logbook $logbook): View|RedirectResponse
    {
        $penempatan = $this->getPenempatan();

        /*
        |--------------------------------------------------------------------------
        | Pastikan logbook benar-benar milik mahasiswa login.
        |--------------------------------------------------------------------------
        */
        if (
            ! $penempatan ||
            $logbook->penempatan_id !== $penempatan->id
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Hanya draft dan revision.
        |--------------------------------------------------------------------------
        */
        if (
            ! in_array(
                $logbook->status,
                ['draft', 'revision'],
                true
            )
        ) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->withErrors([
                    'logbook' => match ($logbook->status) {
                        'submitted' =>
                        'Logbook yang sudah dikirim ke mentor tidak dapat diedit.',

                        'approved' =>
                        'Logbook yang sudah disetujui mentor tidak dapat diedit.',

                        default =>
                        'Logbook ini tidak dapat diedit.',
                    },
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan relasi tersedia untuk view.
        |--------------------------------------------------------------------------
        */
        $logbook->load([
            'penempatan.mahasiswa.user',
            'penempatan.mentor.user',
            'penempatan.periodeMagang',
        ]);

        return view('mahasiswa.logbook.edit', [
            'penempatan' => $penempatan,
            'logbook' => $logbook,
        ]);
    }

    /**
     * Menyimpan logbook baru sebagai draft.
     */
    public function store(
        StoreLogbookRequest $request
    ): RedirectResponse {
        $penempatan = $this->getPenempatan();

        if (! $penempatan) {
            return back()->withErrors([
                'logbook' =>
                'Anda belum memiliki penempatan magang yang aktif.',
            ]);
        }

        $today = Carbon::today();

        /*
        |--------------------------------------------------------------------------
        | Validasi tanggal
        |--------------------------------------------------------------------------
        */
        $dateError = $this->validateLogbookDate(
            $penempatan,
            $today
        );

        if ($dateError) {
            return back()
                ->withErrors([
                    'logbook' => $dateError,
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Satu logbook per hari
        |--------------------------------------------------------------------------
        */
        $existing = Logbook::query()
            ->where('penempatan_id', $penempatan->id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existing) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->withErrors([
                    'logbook' =>
                    'Logbook untuk hari ini sudah dibuat.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Upload bukti
        |--------------------------------------------------------------------------
        */
        $path = null;

        if ($request->hasFile('bukti_kegiatan')) {
            $path = $request
                ->file('bukti_kegiatan')
                ->store(
                    'logbook/bukti',
                    'public'
                );
        }

        DB::transaction(
            function () use (
                $request,
                $penempatan,
                $today,
                $path
            ): void {
                Logbook::create([
                    'penempatan_id' => $penempatan->id,
                    'tanggal' => $today->toDateString(),

                    'judul_kegiatan' =>
                    $request->validated(
                        'judul_kegiatan'
                    ),

                    'uraian_kegiatan' =>
                    $request->validated(
                        'uraian_kegiatan'
                    ),

                    'hasil_kegiatan' =>
                    $request->validated(
                        'hasil_kegiatan'
                    ),

                    'kendala' =>
                    $request->validated(
                        'kendala'
                    ),

                    'rencana_tindak_lanjut' =>
                    $request->validated(
                        'rencana_tindak_lanjut'
                    ),

                    'bukti_kegiatan' => $path,

                    'status' => 'draft',
                ]);
            }
        );

        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with(
                'success',
                'Logbook berhasil disimpan sebagai draft.'
            );
    }

    /**
     * Update logbook draft/revision.
     */
    public function update(
        StoreLogbookRequest $request,
        Logbook $logbook
    ): RedirectResponse {
        $penempatan = $this->getPenempatan();

        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */
        if (
            ! $penempatan ||
            $logbook->penempatan_id !== $penempatan->id
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Hanya draft dan revision.
        |--------------------------------------------------------------------------
        */
        if (
            ! in_array(
                $logbook->status,
                ['draft', 'revision'],
                true
            )
        ) {
            return redirect()
                ->route('mahasiswa.logbook.index')
                ->withErrors([
                    'logbook' =>
                    'Logbook yang sudah dikirim atau disetujui tidak dapat diedit.',
                ]);
        }

        $path = $logbook->bukti_kegiatan;

        /*
        |--------------------------------------------------------------------------
        | Jika upload bukti baru
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('bukti_kegiatan')) {

            if ($path) {
                Storage::disk('public')
                    ->delete($path);
            }

            $path = $request
                ->file('bukti_kegiatan')
                ->store(
                    'logbook/bukti',
                    'public'
                );
        }

        $logbook->update([
            'judul_kegiatan' =>
            $request->validated(
                'judul_kegiatan'
            ),

            'uraian_kegiatan' =>
            $request->validated(
                'uraian_kegiatan'
            ),

            'hasil_kegiatan' =>
            $request->validated(
                'hasil_kegiatan'
            ),

            'kendala' =>
            $request->validated(
                'kendala'
            ),

            'rencana_tindak_lanjut' =>
            $request->validated(
                'rencana_tindak_lanjut'
            ),

            'bukti_kegiatan' => $path,
        ]);

        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with(
                'success',
                'Logbook berhasil diperbarui.'
            );
    }

    /**
     * Mengirim logbook ke mentor.
     */
    public function submit(Logbook $logbook): RedirectResponse
    {
        $penempatan = $this->getPenempatan();

        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */
        if (
            ! $penempatan ||
            $logbook->penempatan_id !== $penempatan->id
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Hanya draft/revision yang bisa dikirim.
        |--------------------------------------------------------------------------
        */
        if (
            ! in_array(
                $logbook->status,
                ['draft', 'revision'],
                true
            )
        ) {
            return back()->withErrors([
                'logbook' =>
                'Logbook ini tidak dapat dikirim kembali.',
            ]);
        }

        $logbook->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('mahasiswa.logbook.index')
            ->with(
                'success',
                'Logbook berhasil dikirim dan menunggu pemeriksaan mentor.'
            );
    }
}
