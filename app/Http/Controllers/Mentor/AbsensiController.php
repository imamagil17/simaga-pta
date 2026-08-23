<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mentor\ApproveAbsensiRequest;
use App\Http\Requests\Mentor\RejectAbsensiRequest;
use App\Models\Absensi;
use App\Models\Mentor;
use App\Services\SignatureService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    /**
     * Menampilkan daftar absensi mahasiswa bimbingan.
     */
    public function index(): View
    {
        $user = Auth::user();

        $mentor = Mentor::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (! $mentor) {
            abort(404);
        }

        $absensis = Absensi::query()
            ->with([
                'penempatan.mahasiswa.user',
                'penempatan.periodeMagang',
                'penempatan.mentor.user',
            ])
            ->whereHas('penempatan', function ($query) use ($mentor) {
                $query
                    ->where('mentor_id', $mentor->id)
                    ->where('status', 'active');
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_masuk')
            ->get();

        $pendingCount = $absensis
            ->where('status_verifikasi', 'pending')
            ->count();

        return view('mentor.absensi.index', [
            'absensis' => $absensis,
            'pendingCount' => $pendingCount,
        ]);
    }

    /**
     * Menampilkan detail absensi.
     */
    public function show(Absensi $absensi): View
    {
        $mentor = Mentor::query()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (! $mentor) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan absensi memang milik mahasiswa bimbingan mentor ini.
        |--------------------------------------------------------------------------
        */
        $absensi->load([
            'penempatan.mahasiswa.user',
            'penempatan.periodeMagang',
            'penempatan.mentor.user',
        ]);

        if (
            ! $absensi->penempatan ||
            $absensi->penempatan->mentor_id !== $mentor->id ||
            $absensi->penempatan->status !== 'active'
        ) {
            abort(404);
        }

        return view('mentor.absensi.show', [
            'absensi' => $absensi,
        ]);
    }

    /**
     * Menyetujui absensi dan menyimpan paraf mentor.
     */
    public function approve(
        ApproveAbsensiRequest $request,
        Absensi $absensi,
        SignatureService $signatureService
    ): RedirectResponse {
        $mentor = Mentor::query()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (! $mentor) {
            abort(404);
        }

        $absensi->load('penempatan');

        /*
        |--------------------------------------------------------------------------
        | Pastikan absensi milik mahasiswa bimbingan mentor.
        |--------------------------------------------------------------------------
        */
        if (
            ! $absensi->penempatan ||
            $absensi->penempatan->mentor_id !== $mentor->id ||
            $absensi->penempatan->status !== 'active'
        ) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Absensi harus sudah lengkap.
        |--------------------------------------------------------------------------
        */
        if ($absensi->jam_masuk === null) {
            return back()->withErrors([
                'absensi' => 'Absensi belum memiliki jam masuk.',
            ]);
        }

        if ($absensi->jam_pulang === null) {
            return back()->withErrors([
                'absensi' => 'Absensi belum memiliki jam pulang.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Tidak boleh ACC dua kali.
        |--------------------------------------------------------------------------
        */
        if ($absensi->status_verifikasi === 'approved') {
            return back()->withErrors([
                'absensi' => 'Absensi ini sudah disetujui.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Paraf mentor
        |--------------------------------------------------------------------------
        */
        $parafPath = null;

        try {
            $parafPath = $signatureService->store(
                $request->validated()['paraf_mentor'],
                'absensi/paraf/mentor'
            );

            DB::transaction(function () use (
                $absensi,
                $parafPath
            ): void {
                $absensi->update([
                    'status_verifikasi' => 'approved',
                    'paraf_mentor' => $parafPath,
                    'paraf_mentor_at' => now(),
                    'alasan_penolakan' => null,
                ]);
            });
        } catch (\Throwable $exception) {
            if ($parafPath) {
                $signatureService->delete($parafPath);
            }

            report($exception);

            return back()->withErrors([
                'absensi' => 'Paraf mentor gagal diproses. Silakan coba lagi.',
            ])->withInput();
        }

        return redirect()
            ->route('mentor.absensi.show', $absensi)
            ->with(
                'success',
                'Absensi berhasil disetujui dan paraf mentor telah disimpan.'
            );
    }

    /**
     * Menolak absensi.
     */
    public function reject(
        RejectAbsensiRequest $request,
        Absensi $absensi
    ): RedirectResponse {
        $mentor = Mentor::query()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (! $mentor) {
            abort(404);
        }

        $absensi->load('penempatan');

        /*
        |--------------------------------------------------------------------------
        | Pastikan absensi milik mahasiswa bimbingan mentor.
        |--------------------------------------------------------------------------
        */
        if (
            ! $absensi->penempatan ||
            $absensi->penempatan->mentor_id !== $mentor->id ||
            $absensi->penempatan->status !== 'active'
        ) {
            abort(404);
        }

        if ($absensi->status_verifikasi === 'approved') {
            return back()->withErrors([
                'absensi' => 'Absensi yang sudah disetujui tidak dapat ditolak.',
            ]);
        }

        $absensi->update([
            'status_verifikasi' => 'rejected',
            'alasan_penolakan' => $request->validated()['alasan_penolakan'],
        ]);

        return redirect()
            ->route('mentor.absensi.show', $absensi)
            ->with(
                'success',
                'Absensi telah ditolak dan alasan penolakan berhasil disimpan.'
            );
    }

    /**
     * Menampilkan rekap absensi seluruh mahasiswa bimbingan mentor.
     */
    public function rekap(): View
    {
        $mentor = Mentor::query()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (! $mentor) {
            abort(404);
        }

        $penempatanIds = $mentor->penempatans()
            ->where('status', 'active')
            ->pluck('id');

        $absensis = Absensi::query()
            ->whereIn('penempatan_id', $penempatanIds)
            ->with([
                'penempatan.mahasiswa.user',
                'penempatan.periodeMagang',
            ])
            ->orderByDesc('tanggal')
            ->get();

        $mahasiswaRekap = $absensis
            ->groupBy('penempatan.mahasiswa_id')
            ->map(function ($items) {
                $mahasiswa = $items->first()->penempatan->mahasiswa;

                return [
                    'mahasiswa' => $mahasiswa,

                    'total' => $items->count(),

                    'hadir' => $items
                        ->where('status_kehadiran', 'hadir')
                        ->count(),

                    'izin' => $items
                        ->where('status_kehadiran', 'izin')
                        ->count(),

                    'sakit' => $items
                        ->where('status_kehadiran', 'sakit')
                        ->count(),

                    'alpa' => $items
                        ->where('status_kehadiran', 'alpa')
                        ->count(),

                    'terlambat' => $items
                        ->whereNotNull('menit_terlambat')
                        ->count(),

                    'total_menit_terlambat' => $items->sum(
                        fn(Absensi $absensi) =>
                        $absensi->menit_terlambat ?? 0
                    ),

                    'approved' => $items
                        ->where('status_verifikasi', 'approved')
                        ->count(),

                    'pending' => $items
                        ->where('status_verifikasi', 'pending')
                        ->count(),

                    'rejected' => $items
                        ->where('status_verifikasi', 'rejected')
                        ->count(),
                ];
            })
            ->sortBy(
                fn($item) => strtolower(
                    $item['mahasiswa']->user->name
                )
            )
            ->values();

        $rekap = [
            'total_mahasiswa' => $mahasiswaRekap->count(),
            'total_absensi' => $absensis->count(),

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

        return view('mentor.absensi.rekap', [
            'mahasiswaRekap' => $mahasiswaRekap,
            'rekap' => $rekap,
        ]);
    }
}