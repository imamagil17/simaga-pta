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

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman absensi mahasiswa.
     */
    public function index(): View
    {
        $user = Auth::user();

        $penempatan = Penempatan::query()
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
     * Menampilkan riwayat dan rekap absensi mahasiswa.
     */
    public function riwayat(): View
    {
        $user = Auth::user();

        $penempatan = Penempatan::query()
            ->with([
                'mahasiswa.user',
                'mentor.user',
                'periodeMagang',
            ])
            ->whereHas('mahasiswa', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->first();

        if (! $penempatan) {
            return view('mahasiswa.absensi.riwayat', [
                'penempatan' => null,
                'absensis' => collect(),
                'rekap' => [
                    'total' => 0,
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alpa' => 0,
                    'terlambat' => 0,
                    'total_menit_terlambat' => 0,
                    'approved' => 0,
                    'pending' => 0,
                    'rejected' => 0,
                ],
            ]);
        }

        $absensis = Absensi::query()
            ->whereHas('penempatan', function ($query) use ($penempatan) {
                $query->where(
                    'mahasiswa_id',
                    $penempatan->mahasiswa_id
                );
            })
            ->with([
                'penempatan.periodeMagang',
                'penempatan.mentor.user',
            ])
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_masuk')
            ->get();

        $rekap = [
            'total' => $absensis->count(),

            'hadir' => $absensis
                ->where('status_kehadiran', 'hadir')
                ->count(),

            'izin' => $absensis
                ->where('status_kehadiran', 'izin')
                ->count(),

            'sakit' => $absensis
                ->where('status_kehadiran', 'sakit')
                ->count(),

            'alpa' => $absensis
                ->where('status_kehadiran', 'alpa')
                ->count(),

            'terlambat' => $absensis
                ->whereNotNull('menit_terlambat')
                ->count(),

            'total_menit_terlambat' => $absensis->sum(
                function (Absensi $absensi) {
                    return $absensi->menit_terlambat ?? 0;
                }
            ),

            'approved' => $absensis
                ->where('status_verifikasi', 'approved')
                ->count(),

            'pending' => $absensis
                ->where('status_verifikasi', 'pending')
                ->count(),

            'rejected' => $absensis
                ->where('status_verifikasi', 'rejected')
                ->count(),
        ];

        return view('mahasiswa.absensi.riwayat', [
            'penempatan' => $penempatan,
            'absensis' => $absensis,
            'rekap' => $rekap,
        ]);
    }

    /**
     * Menyimpan absen masuk mahasiswa.
     */
    public function storeMasuk(
        StoreAbsensiMasukRequest $request,
        WorkScheduleService $workScheduleService
    ): RedirectResponse {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Ambil penempatan aktif mahasiswa
        |--------------------------------------------------------------------------
        */
        $penempatan = Penempatan::query()
            ->with([
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
                'absensi' =>
                'Anda belum memiliki penempatan magang yang aktif.',
            ]);
        }

        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | Pastikan periode magang masih berlaku
        |--------------------------------------------------------------------------
        */
        $periode = $penempatan->periodeMagang;

        if (
            $periode->tanggal_mulai &&
            $now->toDateString() <
            Carbon::parse($periode->tanggal_mulai)->toDateString()
        ) {
            return back()->withErrors([
                'absensi' =>
                'Periode magang Anda belum dimulai.',
            ]);
        }

        if (
            $periode->tanggal_selesai &&
            $now->toDateString() >
            Carbon::parse($periode->tanggal_selesai)->toDateString()
        ) {
            return back()->withErrors([
                'absensi' =>
                'Periode magang Anda sudah berakhir.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Hanya hari kerja
        |--------------------------------------------------------------------------
        */
        if (! $workScheduleService->isHariKerja($now)) {
            return back()->withErrors([
                'absensi' =>
                'Absensi hanya tersedia pada hari kerja Senin sampai Jumat.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Tidak boleh absen masuk dua kali pada hari yang sama
        |--------------------------------------------------------------------------
        */
        $existingAbsensi = Absensi::query()
            ->where('penempatan_id', $penempatan->id)
            ->whereDate(
                'tanggal',
                $now->toDateString()
            )
            ->first();

        if ($existingAbsensi) {
            return back()
                ->withErrors([
                    'absensi' =>
                    'Anda sudah melakukan absen masuk hari ini.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Hitung keterlambatan
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
        |
        | Tidak perlu DB::transaction untuk satu INSERT.
        |
        */
        $validated = $request->validated();

        Absensi::create([
            'penempatan_id' => $penempatan->id,
            'tanggal' => $now->toDateString(),
            'jam_masuk' => $now->format('H:i'),
            'status_kehadiran' => 'hadir',
            'menit_terlambat' => $menitTerlambat,
            'status_verifikasi' => 'pending',
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Redirect sukses
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route('mahasiswa.absensi.index')
            ->with(
                'success',
                'Absen masuk berhasil dicatat.'
            );
    }

    /**
     * Menyimpan absen pulang mahasiswa.
     *
     * Absen pulang hanya meng-update record
     * absensi yang dibuat saat absen masuk.
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
        $penempatan = Penempatan::query()
            ->with([
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
                'absensi' =>
                'Anda belum memiliki penempatan magang yang aktif.',
            ]);
        }

        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | Validasi periode
        |--------------------------------------------------------------------------
        */
        $periode = $penempatan->periodeMagang;

        if (
            $periode->tanggal_mulai &&
            $now->toDateString() <
            Carbon::parse($periode->tanggal_mulai)->toDateString()
        ) {
            return back()->withErrors([
                'absensi' =>
                'Periode magang Anda belum dimulai.',
            ]);
        }

        if (
            $periode->tanggal_selesai &&
            $now->toDateString() >
            Carbon::parse($periode->tanggal_selesai)->toDateString()
        ) {
            return back()->withErrors([
                'absensi' =>
                'Periode magang Anda sudah berakhir.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan hari kerja
        |--------------------------------------------------------------------------
        */
        if (! $workScheduleService->isHariKerja($now)) {
            return back()->withErrors([
                'absensi' =>
                'Absen pulang hanya tersedia pada hari kerja Senin sampai Jumat.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil absensi hari ini
        |--------------------------------------------------------------------------
        */
        $absensi = Absensi::query()
            ->where('penempatan_id', $penempatan->id)
            ->whereDate(
                'tanggal',
                $now->toDateString()
            )
            ->first();

        if (! $absensi) {
            return back()->withErrors([
                'absensi' =>
                'Anda belum melakukan absen masuk hari ini.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Tidak boleh absen pulang dua kali
        |--------------------------------------------------------------------------
        */
        if ($absensi->jam_pulang !== null) {
            return back()->withErrors([
                'absensi' =>
                'Anda sudah melakukan absen pulang hari ini.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Jam pulang normal
        |--------------------------------------------------------------------------
        */
        $jamPulangNormal = $workScheduleService->jamPulang($now);

        if ($jamPulangNormal === null) {
            return back()->withErrors([
                'absensi' =>
                'Jam pulang tidak tersedia pada hari ini.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Belum waktunya pulang
        |--------------------------------------------------------------------------
        */
        $jamPulangSekarang = $now->format('H:i');

        if ($jamPulangSekarang < $jamPulangNormal) {
            return back()->withErrors([
                'absensi' =>
                "Absen pulang belum tersedia. Jam pulang hari ini mulai pukul {$jamPulangNormal}.",
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan paraf mahasiswa
        |--------------------------------------------------------------------------
        */
        $parafPath = null;

        try {
            $validated = $request->validated();

            $parafPath = $signatureService->store(
                $validated['paraf_mahasiswa'],
                'absensi/paraf/mahasiswa'
            );

            $absensi->update([
                'jam_pulang' => $now->format('H:i'),
                'paraf_mahasiswa' => $parafPath,
                'paraf_mahasiswa_at' => $now,
                'status_verifikasi' => 'pending',
            ]);
        } catch (\Throwable $exception) {

            if ($parafPath) {
                $signatureService->delete($parafPath);
            }

            report($exception);

            return back()
                ->withErrors([
                    'absensi' =>
                    'Paraf mahasiswa gagal diproses. Silakan coba lagi.',
                ])
                ->withInput();
        }

        return redirect()
            ->route('mahasiswa.absensi.index')
            ->with(
                'success',
                'Absen pulang berhasil dicatat dan dikirim untuk verifikasi mentor.'
            );
    }
}
