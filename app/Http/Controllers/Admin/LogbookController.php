<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Mentor;
use App\Models\PeriodeMagang;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    /**
     * Query dasar monitoring logbook.
     */
    protected function logbookQuery(Request $request)
    {
        $periodeId = $request->integer('periode_id');
        $mentorId = $request->integer('mentor_id');
        $status = $request->input('status');
        $tanggal = $request->input('tanggal');

        return Logbook::query()
            ->with([
                'penempatan.mahasiswa.user',
                'penempatan.mentor.user',
                'penempatan.periodeMagang',
                'reviewer',
            ])
            ->when($periodeId, function ($query) use ($periodeId) {
                $query->whereHas('penempatan', function ($penempatanQuery) use ($periodeId) {
                    $penempatanQuery->where(
                        'periode_magang_id',
                        $periodeId
                    );
                });
            })
            ->when($mentorId, function ($query) use ($mentorId) {
                $query->whereHas('penempatan', function ($penempatanQuery) use ($mentorId) {
                    $penempatanQuery->where(
                        'mentor_id',
                        $mentorId
                    );
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($tanggal, function ($query) use ($tanggal) {
                $query->whereDate('tanggal', $tanggal);
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at');
    }

    /**
     * Monitoring seluruh logbook.
     */
    public function index(Request $request): View
    {
        $logbooks = $this->logbookQuery($request)->get();

        $periodeMagangs = PeriodeMagang::query()
            ->orderByDesc('tanggal_mulai')
            ->get();

        $mentors = Mentor::query()
            ->with('user')
            ->where('status', 'active')
            ->get()
            ->sortBy(
                fn(Mentor $mentor) =>
                strtolower($mentor->user->name ?? '')
            )
            ->values();

        $rekap = [
            'total' => $logbooks->count(),

            'draft' => $logbooks
                ->where('status', 'draft')
                ->count(),

            'submitted' => $logbooks
                ->where('status', 'submitted')
                ->count(),

            'approved' => $logbooks
                ->where('status', 'approved')
                ->count(),

            'revision' => $logbooks
                ->where('status', 'revision')
                ->count(),
        ];

        return view('admin.logbook.index', [
            'logbooks' => $logbooks,
            'periodeMagangs' => $periodeMagangs,
            'mentors' => $mentors,
            'rekap' => $rekap,
            'filters' => [
                'periode_id' => $request->integer('periode_id'),
                'mentor_id' => $request->integer('mentor_id'),
                'status' => $request->input('status'),
                'tanggal' => $request->input('tanggal'),
            ],
        ]);
    }

    /**
     * Detail logbook untuk monitoring admin.
     */
    public function show(Logbook $logbook): View
    {
        $logbook->load([
            'penempatan.mahasiswa.user',
            'penempatan.mentor.user',
            'penempatan.periodeMagang',
            'reviewer',
        ]);

        return view('admin.logbook.show', [
            'logbook' => $logbook,
        ]);
    }
}
