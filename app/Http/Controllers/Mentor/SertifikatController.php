<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\Sertifikat;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SertifikatController extends Controller
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
     * Ambil penempatan aktif mahasiswa
     * yang merupakan mahasiswa bimbingan mentor.
     */
    protected function getPenempatan(
        Mentor $mentor,
        Mahasiswa $mahasiswa
    ): Penempatan {
        $penempatan = Penempatan::query()
            ->with([
                'mahasiswa.user',
                'mentor.user',
                'periodeMagang',
                'sertifikat',
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

        return $penempatan;
    }

    /**
     * Daftar mahasiswa bimbingan beserta status sertifikat.
     */
    public function index(): View
    {
        $mentor = $this->getMentor();

        $penempatans = Penempatan::query()
            ->with([
                'mahasiswa.user',
                'periodeMagang',
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

            'belum_diajukan' => $penempatans
                ->filter(
                    fn($penempatan) =>
                    ! $penempatan->sertifikat
                )
                ->count(),

            'pending' => $penempatans
                ->filter(
                    fn($penempatan) =>
                    $penempatan->sertifikat?->status === 'pending'
                )
                ->count(),

            'approved' => $penempatans
                ->filter(
                    fn($penempatan) =>
                    $penempatan->sertifikat?->status === 'approved'
                )
                ->count(),

            'rejected' => $penempatans
                ->filter(
                    fn($penempatan) =>
                    $penempatan->sertifikat?->status === 'rejected'
                )
                ->count(),
        ];

        return view('mentor.sertifikat.index', [
            'mentor' => $mentor,
            'penempatans' => $penempatans,
            'rekap' => $rekap,
        ]);
    }

    /**
     * Detail pengajuan sertifikat mahasiswa.
     */
    public function show(
        Mahasiswa $mahasiswa
    ): View {
        $mentor = $this->getMentor();

        $penempatan = $this->getPenempatan(
            $mentor,
            $mahasiswa
        );

        return view('mentor.sertifikat.show', [
            'mentor' => $mentor,
            'penempatan' => $penempatan,
            'sertifikat' => $penempatan->sertifikat,
        ]);
    }

    /**
     * Mengajukan sertifikat.
     */
    public function store(
        Mahasiswa $mahasiswa
    ): RedirectResponse {
        $mentor = $this->getMentor();

        $penempatan = $this->getPenempatan(
            $mentor,
            $mahasiswa
        );

        $sertifikat = $penempatan->sertifikat;

        /*
        |--------------------------------------------------------------------------
        | Sudah disetujui
        |--------------------------------------------------------------------------
        */
        if (
            $sertifikat &&
            $sertifikat->status === 'approved'
        ) {
            return back()->withErrors([
                'sertifikat' =>
                'Sertifikat mahasiswa ini sudah disetujui oleh admin.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Masih menunggu admin
        |--------------------------------------------------------------------------
        */
        if (
            $sertifikat &&
            $sertifikat->status === 'pending'
        ) {
            return back()->withErrors([
                'sertifikat' =>
                'Pengajuan sertifikat mahasiswa ini masih menunggu persetujuan admin.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Jika sebelumnya ditolak, ajukan kembali.
        |--------------------------------------------------------------------------
        */
        if (
            $sertifikat &&
            $sertifikat->status === 'rejected'
        ) {
            $sertifikat->update([
                'status' => 'pending',
                'catatan' => null,
                'approved_by' => null,
                'approved_at' => null,
                'nomor_sertifikat' => null,
                'file_sertifikat' => null,
            ]);
        } else {

            Sertifikat::create([
                'mahasiswa_id' => $mahasiswa->id,
                'penempatan_id' => $penempatan->id,
                'mentor_id' => $mentor->id,
                'status' => 'pending',
            ]);
        }

        return redirect()
            ->route(
                'mentor.sertifikat.show',
                $mahasiswa
            )
            ->with(
                'success',
                'Pengajuan sertifikat berhasil dikirim ke admin.'
            );
    }
}