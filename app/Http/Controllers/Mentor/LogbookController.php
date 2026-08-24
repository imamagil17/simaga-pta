<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Mentor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogbookController extends Controller
{
    /**
     * Ambil mentor yang sedang login.
     */
    protected function getMentor(): Mentor
    {
        $mentor = Mentor::query()
            ->with('user')
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (! $mentor) {
            abort(403, 'Akun mentor tidak valid.');
        }

        return $mentor;
    }

    /**
     * Query logbook yang hanya berasal dari mahasiswa
     * bimbingan mentor yang sedang login.
     */
    protected function logbookQuery(Mentor $mentor)
    {
        return Logbook::query()
            ->with([
                'penempatan.mahasiswa.user',
                'penempatan.mentor.user',
                'penempatan.periodeMagang',
                'reviewer',
            ])
            ->whereHas('penempatan', function ($query) use ($mentor) {
                $query
                    ->where('mentor_id', $mentor->id)
                    ->where('status', 'active');
            });
    }

    /**
     * Daftar logbook mahasiswa bimbingan.
     */
    public function index(Request $request): View
    {
        $mentor = $this->getMentor();

        $status = $request->input('status');

        $logbooks = $this->logbookQuery($mentor)
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->get();

        $rekap = [
            'total' => $this->logbookQuery($mentor)->count(),

            'submitted' => $this->logbookQuery($mentor)
                ->where('status', 'submitted')
                ->count(),

            'approved' => $this->logbookQuery($mentor)
                ->where('status', 'approved')
                ->count(),

            'revision' => $this->logbookQuery($mentor)
                ->where('status', 'revision')
                ->count(),

            'draft' => $this->logbookQuery($mentor)
                ->where('status', 'draft')
                ->count(),
        ];

        return view('mentor.logbook.index', [
            'mentor' => $mentor,
            'logbooks' => $logbooks,
            'rekap' => $rekap,
            'statusFilter' => $status,
        ]);
    }

    /**
     * Detail satu logbook.
     */
    public function show(Logbook $logbook): View
    {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsLogbook(
            $mentor,
            $logbook
        );

        $logbook->load([
            'penempatan.mahasiswa.user',
            'penempatan.mentor.user',
            'penempatan.periodeMagang',
            'reviewer',
        ]);

        return view('mentor.logbook.show', [
            'mentor' => $mentor,
            'logbook' => $logbook,
        ]);
    }

    /**
     * ACC / menyetujui logbook.
     */
    public function approve(Logbook $logbook): RedirectResponse
    {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsLogbook(
            $mentor,
            $logbook
        );

        if ($logbook->status !== 'submitted') {
            return back()->withErrors([
                'logbook' => 'Hanya logbook yang sudah disubmit mahasiswa yang dapat disetujui.',
            ]);
        }

        DB::transaction(function () use ($logbook): void {
            $logbook->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'catatan_mentor' => null,
            ]);
        });

        return redirect()
            ->route('mentor.logbook.show', $logbook)
            ->with(
                'success',
                'Logbook berhasil disetujui.'
            );
    }

    /**
     * Meminta mahasiswa melakukan revisi.
     */
    public function revision(
        Request $request,
        Logbook $logbook
    ): RedirectResponse {
        $mentor = $this->getMentor();

        $this->ensureMentorOwnsLogbook(
            $mentor,
            $logbook
        );

        if ($logbook->status !== 'submitted') {
            return back()->withErrors([
                'logbook' => 'Hanya logbook yang sudah disubmit mahasiswa yang dapat direvisi.',
            ]);
        }

        $validated = $request->validate([
            'catatan_mentor' => [
                'required',
                'string',
                'min:5',
                'max:5000',
            ],
        ], [
            'catatan_mentor.required' => 'Catatan revisi wajib diisi.',
            'catatan_mentor.min' => 'Catatan revisi minimal 5 karakter.',
            'catatan_mentor.max' => 'Catatan revisi maksimal 5000 karakter.',
        ]);

        DB::transaction(function () use (
            $logbook,
            $validated
        ): void {
            $logbook->update([
                'status' => 'revision',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'catatan_mentor' => $validated['catatan_mentor'],
            ]);
        });

        return redirect()
            ->route('mentor.logbook.show', $logbook)
            ->with(
                'success',
                'Logbook dikembalikan kepada mahasiswa untuk diperbaiki.'
            );
    }

    /**
     * Pastikan logbook memang milik mahasiswa bimbingan mentor.
     */
    protected function ensureMentorOwnsLogbook(
        Mentor $mentor,
        Logbook $logbook
    ): void {
        $belongsToMentor = $logbook->penempatan()
            ->where('mentor_id', $mentor->id)
            ->where('status', 'active')
            ->exists();

        if (! $belongsToMentor) {
            abort(403);
        }
    }
}
