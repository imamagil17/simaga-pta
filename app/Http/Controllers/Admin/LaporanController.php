<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Logbook;
use App\Models\Mahasiswa;
use App\Models\Penempatan;
use App\Models\Penilaian;
use App\Models\PeriodeMagang;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Halaman utama laporan.
     */
    public function index(Request $request): View
    {
        $mahasiswas = Mahasiswa::query()
            ->with('user')
            ->where('status', 'active')
            ->get()
            ->sortBy(
                fn($mahasiswa) =>
                strtolower($mahasiswa->user->name ?? '')
            )
            ->values();

        $periodeMagangs = PeriodeMagang::query()
            ->orderByDesc('tanggal_mulai')
            ->get();

        $laporan = collect();

        /*
        |--------------------------------------------------------------------------
        | Tampilkan laporan hanya jika filter dipilih.
        |--------------------------------------------------------------------------
        */
        if (
            $request->filled('mahasiswa_id') ||
            $request->filled('periode_id')
        ) {
            $query = Penempatan::query()
                ->with([
                    'mahasiswa.user',
                    'mentor.user',
                    'periodeMagang',
                ]);

            if ($request->integer('mahasiswa_id')) {
                $query->where(
                    'mahasiswa_id',
                    $request->integer('mahasiswa_id')
                );
            }

            if ($request->integer('periode_id')) {
                $query->where(
                    'periode_magang_id',
                    $request->integer('periode_id')
                );
            }

            $penempatans = $query
                ->orderByDesc('created_at')
                ->get();

            foreach ($penempatans as $penempatan) {
                $laporan->push(
                    $this->buildLaporan($penempatan)
                );
            }
        }

        return view('admin.laporan.index', [
            'laporan' => $laporan,
            'mahasiswas' => $mahasiswas,
            'periodeMagangs' => $periodeMagangs,
            'filters' => [
                'mahasiswa_id' =>
                $request->integer('mahasiswa_id'),

                'periode_id' =>
                $request->integer('periode_id'),
            ],
        ]);
    }

    /**
     * Detail laporan satu mahasiswa.
     */
    public function show(
        Penempatan $penempatan
    ): View {
        $penempatan->load([
            'mahasiswa.user',
            'mentor.user',
            'periodeMagang',
        ]);

        $laporan = $this->buildLaporan(
            $penempatan
        );

        return view('admin.laporan.show', [
            'laporan' => $laporan,
        ]);
    }

    /**
     * Download laporan dalam bentuk PDF.
     */
    public function pdf(
        Penempatan $penempatan
    ) {
        $penempatan->load([
            'mahasiswa.user',
            'mentor.user',
            'periodeMagang',
        ]);

        $laporan = $this->buildLaporan(
            $penempatan
        );

        $pdf = Pdf::loadView(
            'admin.laporan.pdf',
            [
                'laporan' => $laporan,
            ]
        )->setPaper('A4', 'portrait');

        $namaMahasiswa =
            $laporan['mahasiswa']['nama'] ?? 'mahasiswa';

        $filename =
            'laporan-magang-' .
            str_replace(
                ' ',
                '-',
                strtolower($namaMahasiswa)
            ) .
            '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Menyiapkan semua data laporan.
     */
    protected function buildLaporan(
        Penempatan $penempatan
    ): array {
        /*
        |--------------------------------------------------------------------------
        | ABSENSI
        |--------------------------------------------------------------------------
        */
        $absensis = Absensi::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->get();

        $totalAbsensi =
            $absensis->count();

        $hadir =
            $absensis
            ->where(
                'status_kehadiran',
                'hadir'
            )
            ->count();

        $terlambat =
            $absensis
            ->whereNotNull(
                'menit_terlambat'
            )
            ->count();

        $totalMenitTerlambat =
            $absensis->sum(
                fn(Absensi $absensi) =>
                $absensi->menit_terlambat ?? 0
            );

        /*
        |--------------------------------------------------------------------------
        | LOGBOOK
        |--------------------------------------------------------------------------
        */
        $logbooks = Logbook::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->get();

        $totalLogbook =
            $logbooks->count();

        $logbookApproved =
            $logbooks
            ->where(
                'status',
                'approved'
            )
            ->count();

        $logbookSubmitted =
            $logbooks
            ->where(
                'status',
                'submitted'
            )
            ->count();

        $logbookRevision =
            $logbooks
            ->where(
                'status',
                'revision'
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TUGAS
        |--------------------------------------------------------------------------
        */
        $tugas = Tugas::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->get();

        $totalTugas =
            $tugas->count();

        $pengumpulan = PengumpulanTugas::query()
            ->whereIn(
                'tugas_id',
                $tugas->pluck('id')
            )
            ->get();

        $tugasSubmitted =
            $pengumpulan
            ->where(
                'status',
                'submitted'
            )
            ->count();

        $tugasApproved =
            $pengumpulan
            ->where(
                'status',
                'approved'
            )
            ->count();

        $tugasRevision =
            $pengumpulan
            ->where(
                'status',
                'revision'
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PENILAIAN
        |--------------------------------------------------------------------------
        */
        $penilaian = Penilaian::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->first();

        return [
            'penempatan' => $penempatan,

            'mahasiswa' => [
                'id' =>
                $penempatan->mahasiswa->id,

                'nama' =>
                $penempatan->mahasiswa->user->name ?? '-',

                'nim' =>
                $penempatan->mahasiswa->nim ?? '-',

                'perguruan_tinggi' =>
                $penempatan->mahasiswa->perguruan_tinggi ?? '-',

                'program_studi' =>
                $penempatan->mahasiswa->program_studi ?? '-',
            ],

            'mentor' =>
            $penempatan->mentor->user->name ?? '-',

            'periode' =>
            $penempatan->periodeMagang->nama_periode ?? '-',

            'absensi' => [
                'total' =>
                $totalAbsensi,

                'hadir' =>
                $hadir,

                'terlambat' =>
                $terlambat,

                'total_menit_terlambat' =>
                $totalMenitTerlambat,
            ],

            'logbook' => [
                'total' =>
                $totalLogbook,

                'approved' =>
                $logbookApproved,

                'submitted' =>
                $logbookSubmitted,

                'revision' =>
                $logbookRevision,
            ],

            'tugas' => [
                'total' =>
                $totalTugas,

                'submitted' =>
                $tugasSubmitted,

                'approved' =>
                $tugasApproved,

                'revision' =>
                $tugasRevision,
            ],

            'penilaian' => [
                'status' =>
                $penilaian?->status,

                'nilai_akhir' =>
                $penilaian
                    ? (float) $penilaian->nilai_akhir
                    : null,
            ],
        ];
    }
}
