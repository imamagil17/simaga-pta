<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mentor\RevisionTugasRequest;
use App\Http\Requests\Mentor\ReviewTugasRequest;
use App\Models\Mentor;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TugasReviewController extends Controller
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
            abort(403, 'Profil mentor belum tersedia.');
        }

        return $mentor;
    }

    /**
     * Pastikan tugas milik mahasiswa bimbingan mentor.
     */
    protected function ensureMentorOwnsTask(
        Mentor $mentor,
        Tugas $tugas
    ): void {
        $belongsToMentor = $tugas
            ->penempatan()
            ->where('mentor_id', $mentor->id)
            ->where('status', 'active')
            ->exists();

        if (! $belongsToMentor) {
            abort(403);
        }
    }

    /**
     * Pastikan pengumpulan terkait tugas
     * dan mahasiswa bimbingan mentor.
     */
    protected function ensureMentorOwnsSubmission(
        Mentor $mentor,
        Tugas $tugas,
        PengumpulanTugas $pengumpulan
    ): void {
        if ($pengumpulan->tugas_id !== $tugas->id) {
            abort(403);
        }

        $belongsToMentor = $tugas
            ->penempatan()
            ->where('mentor_id', $mentor->id)
            ->where('status', 'active')
            ->exists();

        if (! $belongsToMentor) {
            abort(403);
        }

        $belongsToPlacement = $pengumpulan
            ->mahasiswa()
            ->whereKey(
                $tugas->penempatan->mahasiswa_id
            )
            ->exists();

        if (! $belongsToPlacement) {
            abort(403);
        }
    }

    /**
     * Halaman pemeriksaan pengumpulan.
     */
    public function show(
        Tugas $tugas,
        PengumpulanTugas $pengumpulan
    ): View {
        $mentor = $this->getMentor();

        $tugas->load([
            'penempatan.mahasiswa.user',
            'penempatan.mentor.user',
            'penempatan.periodeMagang',
            'creator',
        ]);

        $this->ensureMentorOwnsSubmission(
            $mentor,
            $tugas,
            $pengumpulan
        );

        $pengumpulan->load([
            'mahasiswa.user',
            'tugas',
            'reviewer',
        ]);

        return view('mentor.tugas.review', [
            'mentor' => $mentor,
            'tugas' => $tugas,
            'pengumpulan' => $pengumpulan,
        ]);
    }

    /**
     * Menyetujui / menyelesaikan pemeriksaan tugas.
     */
    public function review(
        ReviewTugasRequest $request,
        Tugas $tugas,
        PengumpulanTugas $pengumpulan
    ): RedirectResponse {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsSubmission(
            $mentor,
            $tugas,
            $pengumpulan
        );

        if ($pengumpulan->status !== 'submitted') {
            return back()->withErrors([
                'tugas' =>
                'Hanya pengumpulan yang berstatus submitted yang dapat dinilai.',
            ]);
        }

        $validated = $request->validated();

        DB::transaction(function () use (
            $pengumpulan,
            $validated
        ): void {
            $pengumpulan->update([
                'status' => 'reviewed',
                'nilai' => $validated['nilai'],
                'catatan_mentor' =>
                $validated['catatan_mentor'] ?? null,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
        });

        return redirect()
            ->route(
                'mentor.tugas.review.show',
                [
                    $tugas,
                    $pengumpulan,
                ]
            )
            ->with(
                'success',
                'Pengumpulan tugas berhasil dinilai.'
            );
    }

    /**
     * Meminta mahasiswa melakukan revisi.
     */
    public function revision(
        RevisionTugasRequest $request,
        Tugas $tugas,
        PengumpulanTugas $pengumpulan
    ): RedirectResponse {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsSubmission(
            $mentor,
            $tugas,
            $pengumpulan
        );

        if ($pengumpulan->status !== 'submitted') {
            return back()->withErrors([
                'tugas' =>
                'Hanya pengumpulan yang berstatus submitted yang dapat diminta revisi.',
            ]);
        }

        $validated = $request->validated();

        DB::transaction(function () use (
            $pengumpulan,
            $validated
        ): void {
            $pengumpulan->update([
                'status' => 'revision',
                'catatan_mentor' =>
                $validated['catatan_mentor'],
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
        });

        return redirect()
            ->route(
                'mentor.tugas.review.show',
                [
                    $tugas,
                    $pengumpulan,
                ]
            )
            ->with(
                'success',
                'Tugas dikembalikan kepada mahasiswa untuk diperbaiki.'
            );
    }
}
