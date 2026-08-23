<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // User dengan password sementara wajib menggantinya
        // sebelum dapat melanjutkan ke dashboard.
        if ($user && $user->must_change_password) {
            return redirect()->route('force-password.edit');
        }

        if ($user && $user->isAdministrator()) {
            return redirect()->intended(
                route('admin.dashboard', absolute: false)
            );
        }

        if ($user && $user->isMentor()) {
            return redirect()->intended(
                route('mentor.dashboard', absolute: false)
            );
        }

        if ($user && $user->isMahasiswa()) {
            return redirect()->intended(
                route('mahasiswa.dashboard', absolute: false)
            );
        }

        return redirect()->intended(
            route('dashboard', absolute: false)
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}