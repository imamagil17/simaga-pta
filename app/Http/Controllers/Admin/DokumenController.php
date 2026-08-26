<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\Mahasiswa;
use App\Models\PeriodeMagang;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class DokumenController extends Controller
{
    /**
     * Query dasar monitoring dokumen.
     */
    protected function dokumenQuery(Request $request)
    {
        return Dokumen::query()
            ->with([
                'mahasiswa.user',
                'penempatan.mentor.user',
                'penempatan.periodeMagang',
                'verifier',
            ])

            ->when(
                $request->input('status'),
                function ($query, $status) {
                    $query->where('status', $status);
                }
            )

            ->when(
                $request->input('jenis_dokumen'),
                function ($query, $jenis) {
                    $query->where(
                        'jenis_dokumen',
                        $jenis
                    );
                }
            )

            ->when(
                $request->integer('mahasiswa_id'),
                function ($query, $mahasiswaId) {
                    $query->where(
                        'mahasiswa_id',
                        $mahasiswaId
                    );
                }
            )

            ->when(
                $request->integer('periode_id'),
                function ($query, $periodeId) {
                    $query->whereHas(
                        'penempatan',
                        function ($q) use ($periodeId) {
                            $q->where(
                                'periode_magang_id',
                                $periodeId
                            );
                        }
                    );
                }
            )

            ->orderByDesc('created_at');
    }

    /**
     * Monitoring seluruh dokumen.
     */
    public function index(Request $request): View
    {
        $dokumen = $this
            ->dokumenQuery($request)
            ->get();

        $mahasiswas = Mahasiswa::query()
            ->with('user')
            ->where('status', 'active')
            ->get()
            ->sortBy(
                fn($mahasiswa) =>
                strtolower(
                    $mahasiswa->user->name ?? ''
                )
            )
            ->values();

        $periodeMagangs = PeriodeMagang::query()
            ->orderByDesc('tanggal_mulai')
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

        return view('admin.dokumen.index', [
            'dokumen' => $dokumen,
            'mahasiswas' => $mahasiswas,
            'periodeMagangs' => $periodeMagangs,
            'rekap' => $rekap,
            'filters' => [
                'status' =>
                $request->input('status'),

                'jenis_dokumen' =>
                $request->input('jenis_dokumen'),

                'mahasiswa_id' =>
                $request->integer(
                    'mahasiswa_id'
                ),

                'periode_id' =>
                $request->integer(
                    'periode_id'
                ),
            ],
        ]);
    }

    /**
     * Detail dokumen.
     */
    public function show(
        Dokumen $dokumen
    ): View {
        $dokumen->load([
            'mahasiswa.user',
            'penempatan.mentor.user',
            'penempatan.periodeMagang',
            'verifier',
        ]);

        return view('admin.dokumen.show', [
            'dokumen' => $dokumen,
        ]);
    }

    /**
     * Preview dokumen private.
     */
    public function preview(
        Dokumen $dokumen
    ): Response {
        if (! $dokumen->path_file) {
            abort(404);
        }

        $disk = Storage::disk('local');

        if (! $disk->exists($dokumen->path_file)) {
            abort(404);
        }

        $mime = $dokumen->mime_type
            ?: $disk->mimeType(
                $dokumen->path_file
            );

        if (! in_array($mime, [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/webp',
        ], true)) {
            return response()->redirectToRoute(
                'admin.dokumen.download',
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
                    addslashes(
                        $dokumen->nama_file
                    ) .
                    '"',

                'Cache-Control' =>
                'private, no-store',
            ]
        );
    }

    /**
     * Download dokumen private.
     */
    public function download(
        Dokumen $dokumen
    ) {
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
}
