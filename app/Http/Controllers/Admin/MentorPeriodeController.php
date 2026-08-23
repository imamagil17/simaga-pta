<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use App\Models\MentorPeriode;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MentorPeriodeController extends Controller
{
    /**
     * Menampilkan daftar mentor yang ditugaskan pada periode.
     */
    public function index(PeriodeMagang $periodeMagang): View
    {
        $periodeMagang->load([
            'mentorPeriodes.mentor.user',
        ]);

        $assignedMentorIds = $periodeMagang->mentorPeriodes
            ->pluck('mentor_id');

        $availableMentors = Mentor::with('user')
            ->where('status', 'active')
            ->whereNotIn('id', $assignedMentorIds)
            ->whereHas('user', function ($query) {
                $query->where('status', 'active');
            })
            ->orderBy(
                User::select('name')
                    ->whereColumn('users.id', 'mentors.user_id')
            )
            ->get();

        return view('admin.periode-magangs.mentors.index', [
            'periodeMagang' => $periodeMagang,
            'mentorPeriodes' => $periodeMagang->mentorPeriodes,
            'availableMentors' => $availableMentors,
        ]);
    }

    /**
     * Menambahkan mentor ke periode.
     */
    public function store(Request $request, PeriodeMagang $periodeMagang): RedirectResponse
    {
        $validated = $request->validate([
            'mentor_id' => [
                'required',
                'integer',
                Rule::exists('mentors', 'id')
                    ->where(fn ($query) => $query->where('status', 'active')),
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'mentor_id.required' => 'Mentor wajib dipilih.',
            'mentor_id.exists' => 'Mentor yang dipilih tidak tersedia.',
            'status.required' => 'Status penugasan wajib dipilih.',
            'status.in' => 'Status penugasan tidak valid.',
            'keterangan.max' => 'Keterangan maksimal 1000 karakter.',
        ]);

        $mentor = Mentor::with('user')
            ->findOrFail($validated['mentor_id']);

        if ($mentor->status !== 'active' || $mentor->user->status !== 'active') {
            return back()
                ->withErrors([
                    'mentor_id' => 'Mentor yang dipilih tidak aktif.',
                ])
                ->withInput();
        }

        if (
            MentorPeriode::where('periode_magang_id', $periodeMagang->id)
                ->where('mentor_id', $mentor->id)
                ->exists()
        ) {
            return back()
                ->withErrors([
                    'mentor_id' => 'Mentor tersebut sudah ditugaskan pada periode ini.',
                ])
                ->withInput();
        }

        MentorPeriode::create([
            'periode_magang_id' => $periodeMagang->id,
            'mentor_id' => $mentor->id,
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route('admin.periode-magangs.mentors.index', $periodeMagang)
            ->with('success', 'Mentor berhasil ditambahkan ke periode magang.');
    }

    /**
     * Menonaktifkan penugasan mentor pada periode.
     */
    public function update(Request $request, MentorPeriode $mentorPeriode): RedirectResponse
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);

        $mentorPeriode->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Status penugasan mentor berhasil diperbarui.');
    }
}