<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use App\Models\PeriodeMagang;
use App\Models\Tugas;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    /**
     * Query seluruh tugas untuk monitoring admin.
     */
    protected function tugasQuery(Request $request)
    {
        return Tugas::query()
            ->with([
                'penempatan.mahasiswa.user',
                'penempatan.mentor.user',
                'penempatan.periodeMagang',
                'creator',
                'pengumpulan.mahasiswa.user',
                'pengumpulan.reviewer',
            ])
            ->when(
                $request->input('status'),
                function ($query, $status) {
                    $query->where('status', $status);
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
            ->when(
                $request->integer('mentor_id'),
                function ($query, $mentorId) {
                    $query->whereHas(
                        'penempatan',
                        function ($q) use ($mentorId) {
                            $q->where(
                                'mentor_id',
                                $mentorId
                            );
                        }
                    );
                }
            )
            ->orderByDesc('created_at');
    }

    /**
     * Monitoring tugas.
     */
    public function index(Request $request): View
    {
        $tugas = $this->tugasQuery($request)->get();

        $periodeMagangs = PeriodeMagang::query()
            ->orderByDesc('tanggal_mulai')
            ->get();

        $mentors = Mentor::query()
            ->with('user')
            ->where('status', 'active')
            ->get()
            ->sortBy(
                fn($mentor) =>
                strtolower($mentor->user->name ?? '')
            )
            ->values();

        $rekap = [
            'total' => $tugas->count(),

            'draft' => $tugas
                ->where('status', 'draft')
                ->count(),

            'published' => $tugas
                ->where('status', 'published')
                ->count(),

            'closed' => $tugas
                ->where('status', 'closed')
                ->count(),

            'submitted' => $tugas
                ->flatMap(
                    fn($tugas) =>
                    $tugas->pengumpulan
                )
                ->where('status', 'submitted')
                ->count(),

            'reviewed' => $tugas
                ->flatMap(
                    fn($tugas) =>
                    $tugas->pengumpulan
                )
                ->where('status', 'reviewed')
                ->count(),

            'revision' => $tugas
                ->flatMap(
                    fn($tugas) =>
                    $tugas->pengumpulan
                )
                ->where('status', 'revision')
                ->count(),
        ];

        return view('admin.tugas.index', [
            'tugas' => $tugas,
            'periodeMagangs' => $periodeMagangs,
            'mentors' => $mentors,
            'rekap' => $rekap,
            'filters' => [
                'status' => $request->input('status'),
                'periode_id' => $request->integer(
                    'periode_id'
                ),
                'mentor_id' => $request->integer(
                    'mentor_id'
                ),
            ],
        ]);
    }

    /**
     * Detail tugas untuk monitoring admin.
     */
    public function show(Tugas $tugas): View
    {
        $tugas->load([
            'penempatan.mahasiswa.user',
            'penempatan.mentor.user',
            'penempatan.periodeMagang',
            'creator',
            'pengumpulan.mahasiswa.user',
            'pengumpulan.reviewer',
        ]);

        return view('admin.tugas.show', [
            'tugas' => $tugas,
        ]);
    }
}
