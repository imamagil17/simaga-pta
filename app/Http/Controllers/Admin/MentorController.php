<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMentorRequest;
use App\Http\Requests\Admin\UpdateMentorRequest;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MentorController extends Controller
{
    /**
     * Display a listing of the mentors.
     */
    public function index(): View
    {
        $mentors = User::with('mentor')
            ->where('role', 'mentor')
            ->orderBy('name')
            ->get();

        return view('admin.mentors.index', compact('mentors'));
    }

    /**
     * Menampilkan form untuk melengkapi profil mentor.
     */
    public function create(User $user): View
    {
        if ($user->role !== 'mentor') {
            abort(404);
        }

        if ($user->mentor) {
            abort(404);
        }

        return view('admin.mentors.create', compact('user'));
    }

        /**
     * Menampilkan form edit profil mentor.
     */
    public function edit(User $user): View
    {
        if ($user->role !== 'mentor') {
            abort(404);
        }

        $mentor = $user->mentor;

        if (! $mentor) {
            abort(404);
        }

        return view('admin.mentors.edit', compact('user', 'mentor'));
    }

    /**
     * Menampilkan detail profil mentor.
     */
    public function show(User $user): View
    {
        if ($user->role !== 'mentor') {
            abort(404);
        }

        $mentor = $user->mentor;

        if (! $mentor) {
            abort(404);
        }

        return view('admin.mentors.show', compact('user', 'mentor'));
    }

    /**
     * Menyimpan profil mentor baru ke basis data.
     */
    public function store(StoreMentorRequest $request, User $user): RedirectResponse
    {
        if ($user->role !== 'mentor') {
            abort(404);
        }

        if ($user->mentor) {
            abort(404);
        }

        $validated = $request->validated();

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $extension = $request->file('foto')->getClientOriginalExtension();
            $fileName = 'foto_mentor_' . Str::uuid() . '.' . $extension;
            $fotoPath = $request->file('foto')->storeAs('mentors', $fileName, 'public');
        }

        Mentor::create([
            'user_id' => $user->id,
            'nip' => $validated['nip'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'bagian' => $validated['bagian'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'agama' => $validated['agama'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'foto' => $fotoPath,
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route('admin.mentors.index')
            ->with('success', 'Profil mentor berhasil disimpan.');
    }

    /**
     * Memperbarui profil mentor.
     */
    public function update(
        UpdateMentorRequest $request,
        User $user
    ): RedirectResponse {
        if ($user->role !== 'mentor') {
            abort(404);
        }

        $mentor = $user->mentor;

        if (! $mentor) {
            abort(404);
        }

        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Update Foto
        |--------------------------------------------------------------------------
        */
        $fotoPath = $mentor->foto;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');

            $extension = $foto->getClientOriginalExtension();

            $fileName = 'foto_mentor_' . Str::uuid() . '.' . $extension;

            $fotoPath = $foto->storeAs(
                'mentors',
                $fileName,
                'public'
            );

            // Hapus foto lama setelah file baru berhasil disimpan.
            if (
                $mentor->foto &&
                Storage::disk('public')->exists($mentor->foto)
            ) {
                Storage::disk('public')->delete($mentor->foto);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Data Mentor
        |--------------------------------------------------------------------------
        */
        $mentor->update([
            'nip' => $validated['nip'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'bagian' => $validated['bagian'] ?? null,
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'agama' => $validated['agama'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'foto' => $fotoPath,
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route('admin.mentors.index')
            ->with('success', 'Profil mentor berhasil diperbarui.');
    }
}