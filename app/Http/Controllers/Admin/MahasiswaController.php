<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMahasiswaRequest;
use App\Http\Requests\Admin\UpdateMahasiswaRequest;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MahasiswaController extends Controller
{
    public function index(): View
    {
        $mahasiswas = User::with('mahasiswa')
            ->where('role', 'mahasiswa')
            ->orderBy('name')
            ->get();

        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function create(User $user): View
    {
        if ($user->role !== 'mahasiswa') {
            abort(404);
        }

        if ($user->mahasiswa) {
            abort(404);
        }

        return view('admin.mahasiswa.create', compact('user'));
    }

    public function store(
        StoreMahasiswaRequest $request,
        User $user
    ): RedirectResponse {
        if ($user->role !== 'mahasiswa') {
            abort(404);
        }

        if ($user->mahasiswa) {
            abort(404);
        }

        $validated = $request->validated();

        $fotoPath = null;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');

            $fileName = 'foto_mahasiswa_' .
                Str::uuid() .
                '.' .
                $foto->getClientOriginalExtension();

            $fotoPath = $foto->storeAs(
                'mahasiswa',
                $fileName,
                'public'
            );
        }

        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $validated['nim'],
            'perguruan_tinggi' => $validated['perguruan_tinggi'],
            'program_studi' => $validated['program_studi'],
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'agama' => $validated['agama'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'foto' => $fotoPath,
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Profil mahasiswa berhasil disimpan.');
    }

    public function show(User $user): View
    {
        if ($user->role !== 'mahasiswa') {
            abort(404);
        }

        $mahasiswa = $user->mahasiswa;

        if (! $mahasiswa) {
            abort(404);
        }

        return view('admin.mahasiswa.show', compact(
            'user',
            'mahasiswa'
        ));
    }

    public function edit(User $user): View
    {
        if ($user->role !== 'mahasiswa') {
            abort(404);
        }

        $mahasiswa = $user->mahasiswa;

        if (! $mahasiswa) {
            abort(404);
        }

        return view('admin.mahasiswa.edit', compact(
            'user',
            'mahasiswa'
        ));
    }

    public function update(
        UpdateMahasiswaRequest $request,
        User $user
    ): RedirectResponse {
        if ($user->role !== 'mahasiswa') {
            abort(404);
        }

        $mahasiswa = $user->mahasiswa;

        if (! $mahasiswa) {
            abort(404);
        }

        $validated = $request->validated();

        $fotoPath = $mahasiswa->foto;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');

            $fileName = 'foto_mahasiswa_' .
                Str::uuid() .
                '.' .
                $foto->getClientOriginalExtension();

            $fotoPath = $foto->storeAs(
                'mahasiswa',
                $fileName,
                'public'
            );

            if (
                $mahasiswa->foto &&
                Storage::disk('public')->exists($mahasiswa->foto)
            ) {
                Storage::disk('public')->delete($mahasiswa->foto);
            }
        }

        $mahasiswa->update([
            'nim' => $validated['nim'],
            'perguruan_tinggi' => $validated['perguruan_tinggi'],
            'program_studi' => $validated['program_studi'],
            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
            'agama' => $validated['agama'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'foto' => $fotoPath,
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Profil mahasiswa berhasil diperbarui.');
    }
}