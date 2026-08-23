<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StoreAbsensiMasukRequest;
use App\Http\Requests\Mahasiswa\StoreAbsensiPulangRequest;
use App\Models\Absensi;
use App\Models\Penempatan;
use App\Services\SignatureService;
use App\Services\WorkScheduleService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman absensi mahasiswa.
     */
    public function index(): View
    {
        $user = Auth::user();

        $penempatan = Penempatan::with([
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

        $absensiHariIni = null;

        if ($penempatan) {
            $absensiHariIni = Absensi::query()
                ->where('penempatan_id', $penempatan->id)
                ->whereDate('tanggal', today())
                ->first();
        }

        return view('mahasiswa.absensi.index', [
            'penempatan' => $penempatan,
            'absensiHariIni' => $absensiHariIni,
        ]);
    }

    /**
     * Menyimpan absen masuk mahasiswa.
     */
    public function storeMasuk(
        StoreAbsensiMasukRequest $request
    ): RedirectResponse {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Ambil penempatan aktif mahasiswa
        |--------------------------------------------------------------------------
        */
        $penempatan = Penempatan::with([
            'mahasiswa.user',
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

        if (! $penempatan) {
            return back()->withErrors([
                'absensi' => 'Anda belum memiliki penempatan magang yang aktif.',
            ]);
        }

        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | Pastikan tanggal masih berada dalam periode magang
        |--------------------------------------------------------------------------
        */
        $periode = $penempatan->periodeMagang;

        if (
            $periode->tanggal_mulai &&
            $now->toDateString() < Carbon::parse(
                $periode->tanggal_mulai
            )->toDateString()
        ) {
            return back()->withErrors([
                'absensi' => 'Periode magang Anda belum dimulai.',
            ]);
        }

        if (
            $periode->tanggal_selesai &&
            $now->toDateString() > Carbon::parse(
                $periode->tanggal_selesai
            )->toDateString()
        ) {
            return back()->withErrors([
                'absensi' => 'Periode magang Anda sudah berakhir.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan hari kerja
        |--------------------------------------------------------------------------
        */
        $workScheduleService = app(WorkScheduleService::class);

        if (! $workScheduleService->isHariKerja($now)) {
            return back()->withErrors([
                'absensi' => 'Absensi hanya tersedia pada hari kerja Senin sampai Jumat.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Cek absensi hari ini
        |--------------------------------------------------------------------------
        */
        $existingAbsensi = Absensi::query()
            ->where('penempatan_id', $penempatan->id)
            ->whereDate('tanggal', $now->toDateString())
            ->first();

        if ($existingAbsensi) {
            return back()
                ->withErrors([
                    'absensi' => 'Anda sudah melakukan absen masuk hari ini.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Jam masuk normal
        |--------------------------------------------------------------------------
        */
        $jamMasukNormal = $workScheduleService->jamMasuk($now);

        $jamMasukAktual = $now->format('H:i');

        $menitTerlambat = null;

        if ($jamMasukAktual > $jamMasukNormal) {
            $jamNormal = Carbon::createFromFormat(
                'H:i',
                $jamMasukNormal
            );

            $jamAktual = Carbon::createFromFormat(
                'H:i',
                $jamMasukAktual
            );

            $menitTerlambat = $jamNormal->diffInMinutes(
                $jamAktual
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan absensi
        |--------------------------------------------------------------------------
        */
        DB::transaction(function () use (
            $penempatan,
            $now,
            $menitTerlambat,
            $request
        ): void {
            Absensi::create([
                'penempatan_id' => $penempatan->id,
                'tanggal' => $now->toDateString(),
                'jam_masuk' => $now->format('H:i'),
                'status_kehadiran' => 'hadir',
                'menit_terlambat' => $menitTerlambat,
                'status_verifikasi' => 'pending',
                'keterangan' => $request->validated()['keterangan'] ?? null,
            ]);
        });

        return redirect()
            ->route('mahasiswa.absensi.index')
            ->with(
                'success',
                $menitTerlambat !== null
                    ? "Absen masuk berhasil. Anda terlambat {$menitTerlambat} menit."
                    : 'Absen masuk berhasil dicatat.'
            );
    }

    /**
     * Menyimpan absen pulang mahasiswa.
     *
     * Absen pulang hanya meng-update record absensi yang dibuat
     * saat mahasiswa melakukan absen masuk.
     */
    public function storePulang(
        StoreAbsensiPulangRequest $request,
        SignatureService $signatureService,
        WorkScheduleService $workScheduleService
    ): RedirectResponse {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Ambil penempatan aktif mahasiswa
        |--------------------------------------------------------------------------
        */
        $penempatan = Penempatan::with([
            'mahasiswa.user',
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

        if (! $penempatan) {
            return back()->withErrors([
                'absensi' => 'Anda belum memiliki penempatan magang yang aktif.',
            ]);
        }

        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | Pastikan tanggal masih berada dalam periode magang
        |--------------------------------------------------------------------------
        */
        $periode = $penempatan->periodeMagang;

        if (
            $periode->tanggal_mulai &&
            $now->toDateString() < Carbon::parse(
                $periode->tanggal_mulai
            )->toDateString()
        ) {
            return back()->withErrors([
                'absensi' => 'Periode magang Anda belum dimulai.',
            ]);
        }

        if (
            $periode->tanggal_selesai &&
            $now->toDateString() > Carbon::parse(
                $periode->tanggal_selesai
            )->toDateString()
        ) {
            return back()->withErrors([
                'absensi' => 'Periode magang Anda sudah berakhir.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan hari kerja
        |--------------------------------------------------------------------------
        */
        if (! $workScheduleService->isHariKerja($now)) {
            return back()->withErrors([
                'absensi' => 'Absen pulang hanya tersedia pada hari kerja Senin sampai Jumat.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil absensi hari ini
        |--------------------------------------------------------------------------
        */
        $absensi = Absensi::query()
            ->where('penempatan_id', $penempatan->id)
            ->whereDate('tanggal', $now->toDateString())
            ->first();

        if (! $absensi) {
            return back()->withErrors([
                'absensi' => 'Anda belum melakukan absen masuk hari ini.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan belum absen pulang
        |--------------------------------------------------------------------------
        */
        if ($absensi->jam_pulang !== null) {
            return back()->withErrors([
                'absensi' => 'Anda sudah melakukan absen pulang hari ini.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Tentukan jam pulang normal
        |--------------------------------------------------------------------------
        */
        $jamPulangNormal = $workScheduleService->jamPulang($now);

        if ($jamPulangNormal === null) {
            return back()->withErrors([
                'absensi' => 'Jam pulang tidak tersedia pada hari ini.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan sudah memasuki jam pulang
        |--------------------------------------------------------------------------
        */
        $jamPulangSekarang = $now->format('H:i');

        if ($jamPulangSekarang < $jamPulangNormal) {
            return back()->withErrors([
                'absensi' => "Absen pulang belum tersedia. Jam pulang hari ini mulai pukul {$jamPulangNormal}.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan paraf mahasiswa
        |--------------------------------------------------------------------------
        */
        $parafPath = null;

        try {
            $parafPath = $signatureService->store(
                $request->validated()['paraf_mahasiswa'],
                'absensi/paraf/mahasiswa'
            );

            DB::transaction(function () use (
                $absensi,
                $now,
                $parafPath
            ): void {
                $absensi->update([
                    'jam_pulang' => $now->format('H:i'),
                    'paraf_mahasiswa' => $parafPath,
                    'paraf_mahasiswa_at' => $now,
                    'status_verifikasi' => 'pending',
                ]);
            });
        } catch (\Throwable $exception) {
            if ($parafPath) {
                $signatureService->delete($parafPath);
            }

            report($exception);

            return back()->withErrors([
                'absensi' => 'Paraf mahasiswa gagal diproses. Silakan coba lagi.',
            ])->withInput();
        }

        return redirect()
            ->route('mahasiswa.absensi.index')
            ->with(
                'success',
                'Absen pulang berhasil dicatat dan dikirim untuk verifikasi mentor.'
            );
    }
}