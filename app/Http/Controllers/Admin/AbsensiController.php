<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Mentor;
use App\Models\PeriodeMagang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AbsensiController extends Controller
{
    /**
     * Query dasar monitoring absensi.
     *
     * Query ini digunakan bersama oleh:
     * - Monitoring
     * - Export CSV/Excel
     * - Export PDF
     *
     * Sehingga data dan filter selalu konsisten.
     */
    protected function absensiQuery(Request $request)
    {
        $periodeId = $request->integer('periode_id');
        $mentorId = $request->integer('mentor_id');
        $statusVerifikasi = $request->input('status_verifikasi');
        $tanggal = $request->input('tanggal');

        return Absensi::query()
            ->with([
                'penempatan.mahasiswa.user',
                'penempatan.mentor.user',
                'penempatan.periodeMagang',
            ])
            ->when($periodeId, function ($query) use ($periodeId) {
                $query->whereHas('penempatan', function ($penempatanQuery) use ($periodeId) {
                    $penempatanQuery->where(
                        'periode_magang_id',
                        $periodeId
                    );
                });
            })
            ->when($mentorId, function ($query) use ($mentorId) {
                $query->whereHas('penempatan', function ($penempatanQuery) use ($mentorId) {
                    $penempatanQuery->where(
                        'mentor_id',
                        $mentorId
                    );
                });
            })
            ->when($statusVerifikasi, function ($query) use ($statusVerifikasi) {
                $query->where(
                    'status_verifikasi',
                    $statusVerifikasi
                );
            })
            ->when($tanggal, function ($query) use ($tanggal) {
                $query->whereDate(
                    'tanggal',
                    $tanggal
                );
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_masuk');
    }

    /**
     * Monitoring seluruh absensi.
     */
    public function index(Request $request): View
    {
        $absensis = $this->absensiQuery($request)->get();

        $periodeMagangs = PeriodeMagang::query()
            ->orderByDesc('tanggal_mulai')
            ->get();

        $mentors = Mentor::query()
            ->with('user')
            ->where('status', 'active')
            ->get()
            ->sortBy(fn($mentor) => strtolower($mentor->user->name))
            ->values();

        $rekap = [
            'total' => $absensis->count(),

            'hadir' => $absensis
                ->where('status_kehadiran', 'hadir')
                ->count(),

            'terlambat' => $absensis
                ->whereNotNull('menit_terlambat')
                ->count(),

            'total_menit_terlambat' => $absensis->sum(
                fn(Absensi $absensi) =>
                $absensi->menit_terlambat ?? 0
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

        return view('admin.absensi.index', [
            'absensis' => $absensis,
            'periodeMagangs' => $periodeMagangs,
            'mentors' => $mentors,
            'rekap' => $rekap,
            'filters' => [
                'periode_id' => $request->integer('periode_id'),
                'mentor_id' => $request->integer('mentor_id'),
                'status_verifikasi' => $request->input('status_verifikasi'),
                'tanggal' => $request->input('tanggal'),
            ],
        ]);
    }

    /**
     * Export monitoring absensi ke CSV yang dapat dibuka di Excel.
     */
    public function export(Request $request): StreamedResponse
    {
        $absensis = $this->absensiQuery($request)->get();

        $filename = 'laporan-absensi-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($absensis) {

            $handle = fopen('php://output', 'w');

            /*
            |--------------------------------------------------------------------------
            | BOM UTF-8
            |--------------------------------------------------------------------------
            |
            | Membantu Excel membaca karakter Indonesia dengan benar.
            |
            */
            fwrite($handle, "\xEF\xBB\xBF");

            /*
            |--------------------------------------------------------------------------
            | Header laporan
            |--------------------------------------------------------------------------
            */
            fputcsv($handle, [
                'No',
                'NIM',
                'Mahasiswa',
                'Mentor',
                'Periode Magang',
                'Tanggal',
                'Jam Masuk',
                'Jam Pulang',
                'Status Kehadiran',
                'Terlambat (Menit)',
                'Status Verifikasi',
                'Paraf Mahasiswa',
                'Paraf Mentor',
            ], ';');

            /*
            |--------------------------------------------------------------------------
            | Data
            |--------------------------------------------------------------------------
            */
            foreach ($absensis as $index => $absensi) {

                fputcsv($handle, [
                    $index + 1,
                    $absensi->penempatan->mahasiswa->nim ?? '-',
                    $absensi->penempatan->mahasiswa->user->name ?? '-',
                    $absensi->penempatan->mentor->user->name ?? '-',
                    $absensi->penempatan->periodeMagang->nama_periode ?? '-',
                    optional($absensi->tanggal)->format('d-m-Y') ?? '-',

                    $absensi->jam_masuk
                        ? substr($absensi->jam_masuk, 0, 5)
                        : '-',

                    $absensi->jam_pulang
                        ? substr($absensi->jam_pulang, 0, 5)
                        : '-',

                    ucfirst($absensi->status_kehadiran ?? '-'),

                    $absensi->menit_terlambat ?? '-',

                    match ($absensi->status_verifikasi) {
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu',
                    },

                    $absensi->paraf_mahasiswa
                        ? 'Sudah Paraf'
                        : 'Belum Paraf',

                    $absensi->paraf_mentor
                        ? 'Sudah Paraf'
                        : 'Belum Paraf',

                ], ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',

            'Content-Disposition' =>
            'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export monitoring absensi ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $absensis = $this->absensiQuery($request)->get();

        /*
        |--------------------------------------------------------------------------
        | Rekap untuk PDF
        |--------------------------------------------------------------------------
        */
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
                fn(Absensi $absensi) =>
                $absensi->menit_terlambat ?? 0
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

        /*
        |--------------------------------------------------------------------------
        | Buat PDF
        |--------------------------------------------------------------------------
        */
        $pdf = Pdf::loadView('admin.absensi.pdf', [
            'absensis' => $absensis,

            'rekap' => $rekap,

            'filters' => [
                'periode_id' => $request->integer('periode_id'),
                'mentor_id' => $request->integer('mentor_id'),
                'status_verifikasi' => $request->input('status_verifikasi'),
                'tanggal' => $request->input('tanggal'),
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Ukuran PDF
        |--------------------------------------------------------------------------
        |
        | Landscape dipilih karena tabel cukup lebar.
        |
        */
        $pdf->setPaper('a4', 'landscape');

        /*
        |--------------------------------------------------------------------------
        | Download
        |--------------------------------------------------------------------------
        */
        return $pdf->download(
            'laporan-absensi-' . now()->format('Y-m-d-His') . '.pdf'
        );
    }

    /**
     * Detail absensi untuk monitoring admin.
     */
    public function show(Absensi $absensi): View
    {
        $absensi->load([
            'penempatan.mahasiswa.user',
            'penempatan.mentor.user',
            'penempatan.periodeMagang',
        ]);

        return view('admin.absensi.show', [
            'absensi' => $absensi,
        ]);
    }
}
