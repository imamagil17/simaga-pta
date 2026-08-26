<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Logbook;
use App\Models\Penilaian;
use App\Models\Penempatan;
use App\Models\PengumpulanTugas;
use App\Models\Sertifikat;
use App\Models\Tugas;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    /**
     * Ambil penempatan aktif mahasiswa yang sedang login.
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
     * Halaman progress mahasiswa.
     */
    public function index(): View
    {
        $penempatan = $this->getPenempatan();

        if (! $penempatan) {
            return view('mahasiswa.progress.index', [
                'penempatan' => null,
                'progress' => [
                    'absensi' => [
                        'total' => 0,
                        'hadir' => 0,
                        'persentase' => 0,
                    ],
                    'logbook' => [
                        'total' => 0,
                        'approved' => 0,
                        'persentase' => 0,
                    ],
                    'tugas' => [
                        'total' => 0,
                        'approved' => 0,
                        'persentase' => 0,
                    ],
                    'penilaian' => [
                        'tersedia' => false,
                        'final' => false,
                        'nilai_akhir' => null,
                    ],
                    'sertifikat' => [
                        'tersedia' => false,
                    ],
                ],
                'persentaseKeseluruhan' => 0,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Absensi
        |--------------------------------------------------------------------------
        */
        $absensiTotal = Absensi::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->count();

        $absensiHadir = Absensi::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->where(
                'status_kehadiran',
                'hadir'
            )
            ->count();

        $absensiPersentase = $absensiTotal > 0
            ? round(
                ($absensiHadir / $absensiTotal) * 100,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Logbook
        |--------------------------------------------------------------------------
        */
        $logbookTotal = Logbook::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->count();

        $logbookApproved = Logbook::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->where(
                'status',
                'approved'
            )
            ->count();

        $logbookPersentase = $logbookTotal > 0
            ? round(
                ($logbookApproved / $logbookTotal) * 100,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Tugas
        |--------------------------------------------------------------------------
        */
        $tugas = Tugas::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->get();

        $tugasTotal = $tugas->count();

        $tugasApproved = PengumpulanTugas::query()
            ->whereIn(
                'tugas_id',
                $tugas->pluck('id')
            )
            ->where(
                'status',
                'approved'
            )
            ->count();

        $tugasPersentase = $tugasTotal > 0
            ? round(
                ($tugasApproved / $tugasTotal) * 100,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Penilaian
        |--------------------------------------------------------------------------
        */
        $penilaian = Penilaian::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Sertifikat
        |--------------------------------------------------------------------------
        */
        $sertifikat = Sertifikat::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->where(
                'status',
                'approved'
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Persentase keseluruhan.
        |
        | Empat komponen utama:
        | Absensi
        | Logbook
        | Tugas
        | Penilaian
        |--------------------------------------------------------------------------
        */
        $komponen = [
            $absensiPersentase,
            $logbookPersentase,
            $tugasPersentase,
            ($penilaian && $penilaian->status === 'final')
                ? 100
                : 0,
        ];

        $persentaseKeseluruhan = round(
            array_sum($komponen) / count($komponen),
            2
        );

        return view('mahasiswa.progress.index', [
            'penempatan' => $penempatan,

            'progress' => [
                'absensi' => [
                    'total' =>
                    $absensiTotal,

                    'hadir' =>
                    $absensiHadir,

                    'persentase' =>
                    $absensiPersentase,
                ],

                'logbook' => [
                    'total' =>
                    $logbookTotal,

                    'approved' =>
                    $logbookApproved,

                    'persentase' =>
                    $logbookPersentase,
                ],

                'tugas' => [
                    'total' =>
                    $tugasTotal,

                    'approved' =>
                    $tugasApproved,

                    'persentase' =>
                    $tugasPersentase,
                ],

                'penilaian' => [
                    'tersedia' =>
                    $penilaian !== null,

                    'final' =>
                    $penilaian?->status === 'final',

                    'nilai_akhir' =>
                    $penilaian
                        ? (float) $penilaian->nilai_akhir
                        : null,
                ],

                'sertifikat' => [
                    'tersedia' =>
                    $sertifikat !== null,
                ],
            ],

            'persentaseKeseluruhan' =>
            $persentaseKeseluruhan,
        ]);
    }
}
