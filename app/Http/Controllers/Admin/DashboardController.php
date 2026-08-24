<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard Administrator.
     */
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Statistik Data Magang
        |--------------------------------------------------------------------------
        */

        $mahasiswaAktif = Mahasiswa::query()
            ->where('status', 'active')
            ->whereHas('user', function ($query) {
                $query->where('status', 'active');
            })
            ->count();

        $mentorAktif = Mentor::query()
            ->where('status', 'active')
            ->whereHas('user', function ($query) {
                $query->where('status', 'active');
            })
            ->count();

        $periodeAktif = PeriodeMagang::query()
            ->where('status', 'active')
            ->count();

        $penempatanAktif = Penempatan::query()
            ->where('status', 'active')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Absensi Hari Ini
        |--------------------------------------------------------------------------
        */

        $absensiHariIni = Absensi::query()
            ->whereDate('tanggal', today())
            ->get();

        $absensiRekap = [
            'total' => $absensiHariIni->count(),

            'hadir' => $absensiHariIni
                ->where('status_kehadiran', 'hadir')
                ->count(),

            'terlambat' => $absensiHariIni
                ->whereNotNull('menit_terlambat')
                ->count(),

            'pending' => $absensiHariIni
                ->where('status_verifikasi', 'pending')
                ->count(),

            'approved' => $absensiHariIni
                ->where('status_verifikasi', 'approved')
                ->count(),

            'rejected' => $absensiHariIni
                ->where('status_verifikasi', 'rejected')
                ->count(),

            'total_menit_terlambat' => $absensiHariIni->sum(
                fn(Absensi $absensi) =>
                $absensi->menit_terlambat ?? 0
            ),
        ];

        /*
        |--------------------------------------------------------------------------
        | Absensi Menunggu Verifikasi
        |--------------------------------------------------------------------------
        */

        $absensiPending = Absensi::query()
            ->with([
                'penempatan.mahasiswa.user',
                'penempatan.mentor.user',
            ])
            ->where('status_verifikasi', 'pending')
            ->whereNotNull('jam_pulang')
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_masuk')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Mahasiswa Terbaru
        |--------------------------------------------------------------------------
        */

        $mahasiswaTerbaru = Mahasiswa::query()
            ->with('user')
            ->where('status', 'active')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'mahasiswaAktif' => $mahasiswaAktif,
            'mentorAktif' => $mentorAktif,
            'periodeAktif' => $periodeAktif,
            'penempatanAktif' => $penempatanAktif,
            'absensiRekap' => $absensiRekap,
            'absensiPending' => $absensiPending,
            'mahasiswaTerbaru' => $mahasiswaTerbaru,
        ]);
    }
}
