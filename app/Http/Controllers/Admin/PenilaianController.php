<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penilaian;
use App\Models\PeriodeMagang;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    /**
     * Query dasar monitoring penilaian.
     */
    protected function penilaianQuery(Request $request)
    {
        return Penilaian::query()
            ->with([
                'mahasiswa.user',
                'mentor.user',
                'penempatan.periodeMagang',
                'finalizer',
            ])

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

            ->when(
                $request->integer('mentor_id'),
                function ($query, $mentorId) {
                    $query->where(
                        'mentor_id',
                        $mentorId
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
                $request->input('status'),
                function ($query, $status) {
                    $query->where(
                        'status',
                        $status
                    );
                }
            )

            ->orderByDesc('created_at');
    }

    /**
     * Monitoring seluruh penilaian.
     */
    public function index(Request $request): View
    {
        $penilaian = $this
            ->penilaianQuery($request)
            ->get();

        $mentors = Mentor::query()
            ->with('user')
            ->where('status', 'active')
            ->get()
            ->sortBy(
                fn($mentor) =>
                strtolower(
                    $mentor->user->name ?? ''
                )
            )
            ->values();

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
            'total' => $penilaian->count(),

            'draft' => $penilaian
                ->where('status', 'draft')
                ->count(),

            'final' => $penilaian
                ->where('status', 'final')
                ->count(),

            'rata_rata' => $penilaian->isNotEmpty()
                ? round(
                    $penilaian->avg(
                        fn($item) =>
                        $item->nilai_akhir ?? 0
                    ),
                    2
                )
                : 0,

            'tertinggi' => $penilaian->isNotEmpty()
                ? round(
                    $penilaian->max(
                        fn($item) =>
                        $item->nilai_akhir ?? 0
                    ),
                    2
                )
                : 0,

            'terendah' => $penilaian->isNotEmpty()
                ? round(
                    $penilaian->min(
                        fn($item) =>
                        $item->nilai_akhir ?? 0
                    ),
                    2
                )
                : 0,
        ];

        return view('admin.penilaian.index', [
            'penilaian' => $penilaian,
            'mentors' => $mentors,
            'mahasiswas' => $mahasiswas,
            'periodeMagangs' => $periodeMagangs,
            'rekap' => $rekap,
            'filters' => [
                'periode_id' =>
                $request->integer('periode_id'),

                'mentor_id' =>
                $request->integer('mentor_id'),

                'mahasiswa_id' =>
                $request->integer('mahasiswa_id'),

                'status' =>
                $request->input('status'),
            ],
        ]);
    }

    /**
     * Detail penilaian.
     */
    public function show(
        Penilaian $penilaian
    ): View {
        $penilaian->load([
            'mahasiswa.user',
            'mentor.user',
            'penempatan.periodeMagang',
            'finalizer',
        ]);

        return view('admin.penilaian.show', [
            'penilaian' => $penilaian,
        ]);
    }
}
