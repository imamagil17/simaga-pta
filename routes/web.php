<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForcePasswordChangeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Force Password Change Routes
|--------------------------------------------------------------------------
|
| Digunakan ketika user wajib mengganti password pada login pertama.
|
*/
Route::middleware(['auth', 'force.password'])->group(function () {

    Route::get('/force-change-password', [ForcePasswordChangeController::class, 'edit'])
        ->name('force-password.edit');

    Route::put('/force-change-password', [ForcePasswordChangeController::class, 'update'])
        ->name('force-password.update');
});

/*
|--------------------------------------------------------------------------
| Redirect Generic Dashboard
|--------------------------------------------------------------------------
|
| Mengarahkan user ke dashboard sesuai dengan role-nya.
|
*/
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->isAdministrator()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isMentor()) {
        return redirect()->route('mentor.dashboard');
    }

    if ($user->isMahasiswa()) {
        return redirect()->route('mahasiswa.dashboard');
    }

    abort(403);
})->middleware(['auth', 'force.password'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Administrator Routes
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth', 'role:administrator', 'force.password'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');
            
        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');
    });

/*
|--------------------------------------------------------------------------
| Mentor Routes
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth', 'role:mentor', 'force.password'])
    ->prefix('mentor')
    ->as('mentor.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('mentor.dashboard');
        })->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Mahasiswa Routes
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth', 'role:mahasiswa', 'force.password'])
    ->prefix('mahasiswa')
    ->as('mahasiswa.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('mahasiswa.dashboard');
        })->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
|
*/
Route::middleware(['auth', 'force.password'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';