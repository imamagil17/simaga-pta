<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar seluruh pengguna.
     */
    public function index(): View
    {
        $users = User::orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah pengguna.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Menampilkan form edit pengguna.
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $isAdministrator = $user->isAdministrator();

        $roleRules = $isAdministrator
            ? ['required', Rule::in(['administrator'])]
            : ['required', Rule::in(['mentor', 'mahasiswa'])];

        $statusRules = $isAdministrator
            ? ['required', Rule::in(['active'])]
            : ['required', Rule::in(['active', 'inactive'])];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
            'role' => $roleRules,
            'status' => $statusRules,
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan oleh pengguna lain.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role pengguna tidak valid.',
            'status.required' => 'Status akun wajib diisi.',
            'status.in' => 'Status akun tidak valid.',
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }
    /**
     * Mereset password pengguna.
     */
    public function resetPassword(User $user): RedirectResponse
    {
        $temporaryPassword = Str::random(12);

        $user->update([
            'password' => $temporaryPassword,
            'must_change_password' => true,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Password pengguna berhasil direset.')
            ->with('temporary_password', $temporaryPassword);
    }
    /**
     * Menyimpan pengguna baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:mentor,mahasiswa'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['required', 'in:active,inactive'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan.',
            'role.required' => 'Role pengguna wajib dipilih.',
            'role.in' => 'Role pengguna tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'status.required' => 'Status akun wajib dipilih.',
            'status.in' => 'Status akun tidak valid.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
            'must_change_password' => true,
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dibuat.');
    }

    /**
     * Mengaktifkan atau menonaktifkan akun pengguna.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Proteksi akun administrator yang sedang login
        |--------------------------------------------------------------------------
        */
        if ($user->id === auth()->id()) {
            return back()->withErrors([
                'status' => 'Akun Administrator yang sedang digunakan tidak dapat dinonaktifkan.',
            ]);
        }

        $newStatus = $user->status === 'active'
            ? 'inactive'
            : 'active';

        $user->update([
            'status' => $newStatus,
        ]);

        return back()->with(
            'success',
            $newStatus === 'active'
                ? 'Akun pengguna berhasil diaktifkan.'
                : 'Akun pengguna berhasil dinonaktifkan.'
        );
    }
}