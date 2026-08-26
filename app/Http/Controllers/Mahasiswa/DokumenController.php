<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StoreDokumenRequest;
use App\Models\Dokumen;
use App\Models\Penempatan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DokumenController extends Controller
{
    /**
     * Ambil penempatan aktif mahasiswa.
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
     * Daftar dokumen mahasiswa.
     */
    public function index(): View
    {
        $user = Auth::user();

        $mahasiswa = $user->mahasiswa;

        $penempatan = $this->getPenempatan();

        $dokumen = $mahasiswa
            ? Dokumen::query()
            ->with([
                'penempatan.periodeMagang',
                'verifier',
            ])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderByDesc('created_at')
            ->get()
            : collect();

        return view('mahasiswa.dokumen.index', [
            'mahasiswa' => $mahasiswa,
            'penempatan' => $penempatan,
            'dokumen' => $dokumen,
        ]);
    }

    /**
     * Form upload dokumen.
     */
    public function create(): View
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (! $mahasiswa) {
            abort(
                403,
                'Profil mahasiswa belum tersedia.'
            );
        }

        $penempatan = $this->getPenempatan();

        return view('mahasiswa.dokumen.create', [
            'mahasiswa' => $mahasiswa,
            'penempatan' => $penempatan,
        ]);
    }

    /**
     * Simpan dokumen.
     */
    public function store(
        StoreDokumenRequest $request
    ): RedirectResponse {
        $mahasiswa = Auth::user()->mahasiswa;

        if (! $mahasiswa) {
            return back()->withErrors([
                'dokumen' =>
                'Profil mahasiswa belum tersedia.',
            ]);
        }

        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Penempatan aktif boleh dikaitkan ke dokumen.
        |--------------------------------------------------------------------------
        */
        $penempatan = $this->getPenempatan();

        /*
        |--------------------------------------------------------------------------
        | Satu jenis dokumen aktif per mahasiswa/periode.
        |
        | Jika dokumen sebelumnya revision, mahasiswa boleh upload kembali.
        |--------------------------------------------------------------------------
        */
        $existingQuery = Dokumen::query()
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where(
                'jenis_dokumen',
                $validated['jenis_dokumen']
            );

        if ($penempatan) {
            $existingQuery->where(function ($query) use ($penempatan) {
                $query
                    ->where(
                        'penempatan_id',
                        $penempatan->id
                    )
                    ->orWhereNull('penempatan_id');
            });
        }

        $existing = $existingQuery
            ->latest()
            ->first();

        if (
            $existing &&
            $existing->status !== 'revision'
        ) {
            return back()
                ->withErrors([
                    'jenis_dokumen' =>
                    'Dokumen dengan jenis tersebut sudah pernah diunggah.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan file di storage private.
        |--------------------------------------------------------------------------
        */
        $path = $request
            ->file('file')
            ->store(
                'dokumen/mahasiswa/' . $mahasiswa->id,
                'local'
            );

        $file = $request->file('file');

        /*
        |--------------------------------------------------------------------------
        | Jika revision, hapus file lama kemudian update record.
        |--------------------------------------------------------------------------
        */
        if ($existing) {

            if ($existing->path_file) {
                Storage::disk('local')
                    ->delete($existing->path_file);
            }

            $existing->update([
                'penempatan_id' => $penempatan?->id,
                'nama_dokumen' => $validated['nama_dokumen'],
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'mime_type' => $file->getMimeType(),
                'ukuran_file' => $file->getSize(),
                'status' => 'uploaded',
                'catatan' => null,
                'verified_by' => null,
                'verified_at' => null,
            ]);
        } else {

            Dokumen::create([
                'mahasiswa_id' => $mahasiswa->id,
                'penempatan_id' => $penempatan?->id,
                'jenis_dokumen' => $validated['jenis_dokumen'],
                'nama_dokumen' => $validated['nama_dokumen'],
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'mime_type' => $file->getMimeType(),
                'ukuran_file' => $file->getSize(),
                'status' => 'uploaded',
            ]);
        }

        return redirect()
            ->route('mahasiswa.dokumen.index')
            ->with(
                'success',
                $existing
                    ? 'Dokumen revisi berhasil diunggah kembali.'
                    : 'Dokumen berhasil diunggah.'
            );
    }

    /**
     * Download dokumen milik mahasiswa sendiri.
     */
    public function download(Dokumen $dokumen): StreamedResponse
    {
        $this->ensureOwnDocument($dokumen);

        if (! $dokumen->path_file) {
            abort(404);
        }

        if (! Storage::disk('local')
            ->exists($dokumen->path_file)) {
            abort(404);
        }

        return Storage::disk('local')->download(
            $dokumen->path_file,
            $dokumen->nama_file
        );
    }

    /**
     * Preview file dokumen.
     *
     * Cocok untuk PDF / gambar.
     */
    public function preview(
        Dokumen $dokumen
    ): Response {
        $this->ensureOwnDocument($dokumen);

        if (! $dokumen->path_file) {
            abort(404);
        }

        $disk = Storage::disk('local');

        if (! $disk->exists($dokumen->path_file)) {
            abort(404);
        }

        $mime = $dokumen->mime_type
            ?: $disk->mimeType($dokumen->path_file);

        /*
        |--------------------------------------------------------------------------
        | Preview hanya format yang browser umum bisa tampilkan.
        |--------------------------------------------------------------------------
        */
        if (! in_array($mime, [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/webp',
        ], true)) {
            return response()->redirectToRoute(
                'mahasiswa.dokumen.download',
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
                'Cache-Control' => 'private, no-store',
            ]
        );
    }

    /**
     * Hapus dokumen.
     *
     * Hanya dokumen yang belum diverifikasi.
     */
    public function destroy(
        Dokumen $dokumen
    ): RedirectResponse {
        $this->ensureOwnDocument($dokumen);

        if ($dokumen->status === 'verified') {
            return back()->withErrors([
                'dokumen' =>
                'Dokumen yang sudah diverifikasi tidak dapat dihapus.',
            ]);
        }

        if ($dokumen->path_file) {
            Storage::disk('local')
                ->delete($dokumen->path_file);
        }

        $dokumen->delete();

        return redirect()
            ->route('mahasiswa.dokumen.index')
            ->with(
                'success',
                'Dokumen berhasil dihapus.'
            );
    }

    /**
     * Pastikan dokumen milik mahasiswa yang login.
     */
    protected function ensureOwnDocument(
        Dokumen $dokumen
    ): void {
        $mahasiswa = Auth::user()->mahasiswa;

        if (
            ! $mahasiswa ||
            $dokumen->mahasiswa_id !== $mahasiswa->id
        ) {
            abort(403);
        }
    }
}
