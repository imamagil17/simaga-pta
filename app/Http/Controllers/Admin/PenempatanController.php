<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePenempatanRequest;
use App\Http\Requests\Admin\UpdatePenempatanRequest;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\MentorPeriode;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PenempatanController extends Controller
{
    /**
     * Daftar seluruh penempatan.
     */
    public function index(): View
    {
        $penempatans = Penempatan::with([
            'mahasiswa.user',
            'mentor.user',
            'periodeMagang',
        ])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.penempatan.index', compact('penempatans'));
    }

    /**
     * Form tambah penempatan.
     */
    public function create(): View
    {
        $mahasiswas = Mahasiswa::with('user')
            ->where('status', 'active')
            ->whereHas('user', function ($query) {
                $query->where('status', 'active');
            })
            ->orderBy(
                'id'
            )
            ->get();

        $periodes = PeriodeMagang::query()
            ->where('status', 'active')
            ->orderByDesc('tanggal_mulai')
            ->get();

        $mentorPeriodes = MentorPeriode::with([
            'mentor.user',
        ])
            ->where('status', 'active')
            ->whereHas('mentor', function ($query) {
                $query->where('status', 'active')
                    ->whereHas('user', function ($query) {
                        $query->where('status', 'active');
                    });
            })
            ->get()
            ->groupBy('periode_magang_id');

        $usedMahasiswaIds = Penempatan::query()
            ->where('status', 'active')
            ->pluck('mahasiswa_id');

        $mahasiswas = $mahasiswas
            ->whereNotIn('id', $usedMahasiswaIds)
            ->values();

        return view('admin.penempatan.create', [
            'mahasiswas' => $mahasiswas,
            'periodes' => $periodes,
            'mentorPeriodes' => $mentorPeriodes,
        ]);
    }

    /**
     * Simpan penempatan.
     */
    public function store(StorePenempatanRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $mahasiswa = Mahasiswa::with('user')
            ->findOrFail($validated['mahasiswa_id']);

        $periode = PeriodeMagang::findOrFail(
            $validated['periode_magang_id']
        );

        if (
            $mahasiswa->status !== 'active' ||
            $mahasiswa->user->status !== 'active'
        ) {
            return back()
                ->withErrors([
                    'mahasiswa_id' => 'Mahasiswa yang dipilih tidak aktif.',
                ])
                ->withInput();
        }

        if ($periode->status !== 'active') {
            return back()
                ->withErrors([
                    'periode_magang_id' => 'Periode yang dipilih tidak aktif.',
                ])
                ->withInput();
        }

        $mentorPeriode = MentorPeriode::with([
            'mentor.user',
        ])
            ->where('periode_magang_id', $periode->id)
            ->where('mentor_id', $validated['mentor_id'])
            ->where('status', 'active')
            ->first();

        if (! $mentorPeriode) {
            return back()
                ->withErrors([
                    'mentor_id' => 'Mentor tersebut tidak terdaftar aktif pada periode yang dipilih.',
                ])
                ->withInput();
        }

        if (
            $mentorPeriode->mentor->status !== 'active' ||
            $mentorPeriode->mentor->user->status !== 'active'
        ) {
            return back()
                ->withErrors([
                    'mentor_id' => 'Mentor yang dipilih tidak aktif.',
                ])
                ->withInput();
        }

        if (
            Penempatan::where('mahasiswa_id', $mahasiswa->id)
                ->where('periode_magang_id', $periode->id)
                ->exists()
        ) {
            return back()
                ->withErrors([
                    'mahasiswa_id' => 'Mahasiswa tersebut sudah memiliki penempatan pada periode ini.',
                ])
                ->withInput();
        }

        Penempatan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'periode_magang_id' => $periode->id,
            'mentor_id' => $validated['mentor_id'],
            'status' => $validated['status'],
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route('admin.penempatans.index')
            ->with('success', 'Penempatan mahasiswa berhasil disimpan.');
    }

    /**
     * Detail penempatan.
     */
    public function show(Penempatan $penempatan): View
    {
        $penempatan->load([
            'mahasiswa.user',
            'mentor.user',
            'periodeMagang',
        ]);

        return view('admin.penempatan.show', compact('penempatan'));
    }

    /**
     * Form edit.
     */
    public function edit(Penempatan $penempatan): View
    {
        $penempatan->load([
            'mahasiswa.user',
            'mentor.user',
            'periodeMagang',
        ]);

        $mahasiswas = Mahasiswa::with('user')
            ->where('status', 'active')
            ->whereHas('user', function ($query) {
                $query->where('status', 'active');
            })
            ->orderBy('id')
            ->get();

        $periodes = PeriodeMagang::query()
            ->where('status', 'active')
            ->orderByDesc('tanggal_mulai')
            ->get();

        $mentorPeriodes = MentorPeriode::with([
            'mentor.user',
        ])
            ->where('status', 'active')
            ->whereHas('mentor', function ($query) {
                $query->where('status', 'active')
                    ->whereHas('user', function ($query) {
                        $query->where('status', 'active');
                    });
            })
            ->get()
            ->groupBy('periode_magang_id');

        return view('admin.penempatan.edit', [
            'penempatan' => $penempatan,
            'mahasiswas' => $mahasiswas,
            'periodes' => $periodes,
            'mentorPeriodes' => $mentorPeriodes,
        ]);
    }

    /**
     * Update penempatan.
     */
    public function update(
        UpdatePenempatanRequest $request,
        Penempatan $penempatan
    ): RedirectResponse {
        $validated = $request->validated();

        $mahasiswa = Mahasiswa::with('user')
            ->findOrFail($validated['mahasiswa_id']);

        $periode = PeriodeMagang::findOrFail(
            $validated['periode_magang_id']
        );

        if (
            $mahasiswa->status !== 'active' ||
            $mahasiswa->user->status !== 'active'
        ) {
            return back()
                ->withErrors([
                    'mahasiswa_id' => 'Mahasiswa yang dipilih tidak aktif.',
                ])
                ->withInput();
        }

        if ($periode->status !== 'active') {
            return back()
                ->withErrors([
                    'periode_magang_id' => 'Periode yang dipilih tidak aktif.',
                ])
                ->withInput();
        }

        $mentorPeriode = MentorPeriode::with([
            'mentor.user',
        ])
            ->where('periode_magang_id', $periode->id)
            ->where('mentor_id', $validated['mentor_id'])
            ->where('status', 'active')
            ->first();

        if (! $mentorPeriode) {
            return back()
                ->withErrors([
                    'mentor_id' => 'Mentor tersebut tidak terdaftar aktif pada periode yang dipilih.',
                ])
                ->withInput();
        }

        if (
            $mentorPeriode->mentor->status !== 'active' ||
            $mentorPeriode->mentor->user->status !== 'active'
        ) {
            return back()
                ->withErrors([
                    'mentor_id' => 'Mentor yang dipilih tidak aktif.',
                ])
                ->withInput();
        }

        $duplicateExists = Penempatan::query()
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('periode_magang_id', $periode->id)
            ->where('id', '!=', $penempatan->id)
            ->exists();

        if ($duplicateExists) {
            return back()
                ->withErrors([
                    'mahasiswa_id' => 'Mahasiswa tersebut sudah memiliki penempatan pada periode ini.',
                ])
                ->withInput();
        }

        $penempatan->update([
            'mahasiswa_id' => $mahasiswa->id,
            'periode_magang_id' => $periode->id,
            'mentor_id' => $validated['mentor_id'],
            'status' => $validated['status'],
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route('admin.penempatans.index')
            ->with('success', 'Penempatan mahasiswa berhasil diperbarui.');
    }

    /**
     * Toggle status penempatan.
     */
    public function toggleStatus(Penempatan $penempatan): RedirectResponse
    {
        $penempatan->update([
            'status' => $penempatan->status === 'active'
                ? 'inactive'
                : 'active',
        ]);

        return back()->with(
            'success',
            $penempatan->status === 'active'
                ? 'Penempatan berhasil diaktifkan.'
                : 'Penempatan berhasil dinonaktifkan.'
        );
    }
}