<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Logbook;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\Penilaian;
use App\Models\PengumpulanTugas;
use App\Models\Sertifikat;
use App\Models\Tugas;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class MahasiswaBimbinganController extends Controller
{
    /**
     * Ambil mentor yang sedang login.
     */
    protected function getMentor(): Mentor
    {
        $mentor = Mentor::query()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (! $mentor) {
            abort(
                403,
                'Profil mentor belum tersedia.'
            );
        }

        return $mentor;
    }

    /**
     * Daftar mahasiswa bimbingan.
     */
    public function index(): View
    {
        $mentor = $this->getMentor();

        $penempatans = Penempatan::query()
            ->with([
                'mahasiswa.user',
                'periodeMagang',
                'penilaian',
                'sertifikat',
            ])
            ->where('mentor_id', $mentor->id)
            ->where('status', 'active')
            ->whereHas('mahasiswa', function ($query) {
                $query->where('status', 'active');
            })
            ->whereHas('mahasiswa.user', function ($query) {
                $query->where('status', 'active');
            })
            ->orderByDesc('created_at')
            ->get();

        $rekap = [
            'total' => $penempatans->count(),

            'sudah_dinilai' => $penempatans
                ->filter(
                    fn($penempatan) =>
                    $penempatan->penilaian?->status === 'final'
                )
                ->count(),

            'sertifikat' => $penempatans
                ->filter(
                    fn($penempatan) =>
                    $penempatan->sertifikat?->status === 'approved'
                )
                ->count(),
        ];

        return view('mentor.mahasiswa-bimbingan.index', [
            'mentor' => $mentor,
            'penempatans' => $penempatans,
            'rekap' => $rekap,
        ]);
    }

    /**
     * Detail satu mahasiswa bimbingan.
     */
    public function show(
        Mahasiswa $mahasiswa
    ): View {
        $mentor = $this->getMentor();

        $penempatan = Penempatan::query()
            ->with([
                'mahasiswa.user',
                'mentor.user',
                'periodeMagang',
            ])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('mentor_id', $mentor->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (! $penempatan) {
            abort(
                403,
                'Mahasiswa bukan bagian dari bimbingan Anda.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Absensi
        |--------------------------------------------------------------------------
        */
        $absensis = Absensi::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->get();

        $absensiTotal = $absensis->count();

        $absensiHadir = $absensis
            ->where(
                'status_kehadiran',
                'hadir'
            )
            ->count();

        $absensiTerlambat = $absensis
            ->whereNotNull(
                'menit_terlambat'
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Logbook
        |--------------------------------------------------------------------------
        */
        $logbooks = Logbook::query()
            ->where(
                'penempatan_id',
                $penempatan->id
            )
            ->get();

        $logbookTotal = $logbooks->count();

        $logbookApproved = $logbooks
            ->where(
                'status',
                'approved'
            )
            ->count();

        $logbookSubmitted = $logbooks
            ->where(
                'status',
                'submitted'
            )
            ->count();

        $logbookRevision = $logbooks
            ->where(
                'status',
                'revision'
            )
            ->count();

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

        $pengumpulan = PengumpulanTugas::query()
            ->whereIn(
                'tugas_id',
                $tugas->pluck('id')
            )
            ->get();

        $tugasSubmitted = $pengumpulan
            ->where(
                'status',
                'submitted'
            )
            ->count();

        $tugasApproved = $pengumpulan
            ->where(
                'status',
                'approved'
            )
            ->count();

        $tugasRevision = $pengumpulan
            ->where(
                'status',
                'revision'
            )
            ->count();

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
            ->first();

        return view('mentor.mahasiswa-bimbingan.show', [
            'mentor' => $mentor,
            'penempatan' => $penempatan,

            'absensi' => [
                'total' =>
                $absensiTotal,

                'hadir' =>
                $absensiHadir,

                'terlambat' =>
                $absensiTerlambat,
            ],

            'logbook' => [
                'total' =>
                $logbookTotal,

                'approved' =>
                $logbookApproved,

                'submitted' =>
                $logbookSubmitted,

                'revision' =>
                $logbookRevision,
            ],

            'tugas' => [
                'total' =>
                $tugasTotal,

                'submitted' =>
                $tugasSubmitted,

                'approved' =>
                $tugasApproved,

                'revision' =>
                $tugasRevision,
            ],

            'penilaian' =>
            $penilaian,

            'sertifikat' =>
            $sertifikat,
        ]);
    }
}
