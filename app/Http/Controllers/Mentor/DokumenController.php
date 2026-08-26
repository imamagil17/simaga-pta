<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mentor\RevisionDokumenRequest;
use App\Http\Requests\Mentor\VerifyDokumenRequest;
use App\Models\Dokumen;
use App\Models\Mentor;
use App\Models\Penempatan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /**
     * Mentor yang sedang login.
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
     * Query dasar dokumen mahasiswa bimbingan.
     */
    protected function dokumenQuery(Mentor $mentor)
    {
        return Dokumen::query()
            ->with([
                'mahasiswa.user',
                'penempatan.periodeMagang',
                'verifier',
            ])
            ->whereHas('penempatan', function ($query) use ($mentor) {
                $query
                    ->where('mentor_id', $mentor->id)
                    ->where('status', 'active');
            });
    }

    /**
     * Daftar dokumen mahasiswa bimbingan.
     */
    public function index(): View
    {
        $mentor = $this->getMentor();

        $dokumen = $this->dokumenQuery($mentor)
            ->orderByDesc('created_at')
            ->get();

        $rekap = [
            'total' => $dokumen->count(),

            'uploaded' => $dokumen
                ->where('status', 'uploaded')
                ->count(),

            'verified' => $dokumen
                ->where('status', 'verified')
                ->count(),

            'revision' => $dokumen
                ->where('status', 'revision')
                ->count(),
        ];

        return view('mentor.dokumen.index', [
            'mentor' => $mentor,
            'dokumen' => $dokumen,
            'rekap' => $rekap,
        ]);
    }

    /**
     * Detail dokumen.
     */
    public function show(
        Dokumen $dokumen
    ): View {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsDocument(
            $mentor,
            $dokumen
        );

        $dokumen->load([
            'mahasiswa.user',
            'penempatan.mahasiswa.user',
            'penempatan.mentor.user',
            'penempatan.periodeMagang',
            'verifier',
        ]);

        return view('mentor.dokumen.show', [
            'mentor' => $mentor,
            'dokumen' => $dokumen,
        ]);
    }

    /**
     * Preview dokumen private.
     */
    public function preview(
        Dokumen $dokumen
    ): Response {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsDocument(
            $mentor,
            $dokumen
        );

        if (! $dokumen->path_file) {
            abort(404);
        }

        $disk = Storage::disk('local');

        if (! $disk->exists($dokumen->path_file)) {
            abort(404);
        }

        $mime = $dokumen->mime_type
            ?: $disk->mimeType($dokumen->path_file);

        if (! in_array($mime, [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/webp',
        ], true)) {
            return response()
                ->redirectToRoute(
                    'mentor.dokumen.download',
                    $dokumen
                );
        }

        return response(
            $disk->get($dokumen->path_file),
            200,
            [
                'Content-Type' => $mime,
                'Content-Disposition' =>
                'inline; filename="' .
                    addslashes($dokumen->nama_file) .
                    '"',
                'Cache-Control' =>
                'private, no-store',
            ]
        );
    }

    /**
     * Download dokumen.
     */
    public function download(
        Dokumen $dokumen
    ) {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsDocument(
            $mentor,
            $dokumen
        );

        if (! $dokumen->path_file) {
            abort(404);
        }

        $disk = Storage::disk('local');

        if (! $disk->exists($dokumen->path_file)) {
            abort(404);
        }

        return $disk->download(
            $dokumen->path_file,
            $dokumen->nama_file
        );
    }

    /**
     * Verifikasi dokumen.
     */
    public function verify(
        VerifyDokumenRequest $request,
        Dokumen $dokumen
    ): RedirectResponse {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsDocument(
            $mentor,
            $dokumen
        );

        if ($dokumen->status !== 'uploaded') {
            return back()->withErrors([
                'dokumen' =>
                'Hanya dokumen yang menunggu verifikasi yang dapat diverifikasi.',
            ]);
        }

        $dokumen->update([
            'status' => 'verified',
            'catatan' => $request->input('catatan'),
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()
            ->route(
                'mentor.dokumen.show',
                $dokumen
            )
            ->with(
                'success',
                'Dokumen berhasil diverifikasi.'
            );
    }

    /**
     * Minta revisi.
     */
    public function revision(
        RevisionDokumenRequest $request,
        Dokumen $dokumen
    ): RedirectResponse {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsDocument(
            $mentor,
            $dokumen
        );

        if ($dokumen->status !== 'uploaded') {
            return back()->withErrors([
                'dokumen' =>
                'Hanya dokumen yang menunggu verifikasi yang dapat diminta revisi.',
            ]);
        }

        $dokumen->update([
            'status' => 'revision',
            'catatan' => $request->validated()['catatan'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()
            ->route(
                'mentor.dokumen.show',
                $dokumen
            )
            ->with(
                'success',
                'Dokumen dikembalikan kepada mahasiswa untuk diperbaiki.'
            );
    }

    /**
     * Pastikan dokumen milik mahasiswa bimbingan mentor.
     */
    protected function ensureMentorOwnsDocument(
        Mentor $mentor,
        Dokumen $dokumen
    ): void {
        $belongsToMentor = $dokumen
            ->whereKey($dokumen->id)
            ->whereHas('penempatan', function ($query) use ($mentor) {
                $query
                    ->where('mentor_id', $mentor->id)
                    ->where('status', 'active');
            })
            ->exists();

        if (! $belongsToMentor) {
            abort(403);
        }
    }
}
