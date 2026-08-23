<?php

use App\Http\Controllers\Admin\AbsensiController as AdminAbsensiController;
use App\Http\Controllers\Mahasiswa\AbsensiController as MahasiswaAbsensiController;
use App\Http\Controllers\Mentor\AbsensiController as MentorAbsensiController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\MentorController;
use App\Http\Controllers\Admin\MentorPeriodeController;
use App\Http\Controllers\Admin\PeriodeMagangController;
use App\Http\Controllers\Admin\PenempatanController;
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

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Manajemen Pengguna
        |--------------------------------------------------------------------------
        */
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])
            ->name('users.reset-password');

        Route::put('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        /*
        |--------------------------------------------------------------------------
        | Data Mentor
        |--------------------------------------------------------------------------
        */
        Route::get('/mentors', [MentorController::class, 'index'])
            ->name('mentors.index');

        Route::get('/mentors/{user}/profile/create', [MentorController::class, 'create'])
            ->name('mentors.profile.create');

        Route::post('/mentors/{user}/profile', [MentorController::class, 'store'])
            ->name('mentors.profile.store');

        Route::get('/mentors/{user}/profile/edit', [MentorController::class, 'edit'])
            ->name('mentors.profile.edit');

        Route::put('/mentors/{user}/profile', [MentorController::class, 'update'])
            ->name('mentors.profile.update');

        Route::get('/mentors/{user}/profile', [MentorController::class, 'show'])
            ->name('mentors.profile.show');

        /*
        |--------------------------------------------------------------------------
        | Data Periode Magang
        |--------------------------------------------------------------------------
        */
        Route::get('/periode-magangs', [PeriodeMagangController::class, 'index'])
            ->name('periode-magangs.index');

        Route::get('/periode-magangs/create', [PeriodeMagangController::class, 'create'])
            ->name('periode-magangs.create');

        Route::post('/periode-magangs', [PeriodeMagangController::class, 'store'])
            ->name('periode-magangs.store');

        Route::get('/periode-magangs/{periodeMagang}/edit', [PeriodeMagangController::class, 'edit'])
            ->name('periode-magangs.edit');

        Route::put('/periode-magangs/{periodeMagang}', [PeriodeMagangController::class, 'update'])
            ->name('periode-magangs.update');

        /*
        |--------------------------------------------------------------------------
        | Mentor pada Periode Magang
        |--------------------------------------------------------------------------
        */
        Route::get(
            '/periode-magangs/{periodeMagang}/mentors',
            [MentorPeriodeController::class, 'index']
        )->name('periode-magangs.mentors.index');

        Route::post(
            '/periode-magangs/{periodeMagang}/mentors',
            [MentorPeriodeController::class, 'store']
        )->name('periode-magangs.mentors.store');

        Route::put(
            '/mentor-periodes/{mentorPeriode}',
            [MentorPeriodeController::class, 'update']
        )->name('mentor-periodes.update');

        /*
        |--------------------------------------------------------------------------
        | Data Mahasiswa
        |--------------------------------------------------------------------------
        */
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])
            ->name('mahasiswa.index');

        Route::get('/mahasiswa/{user}/profile/create', [MahasiswaController::class, 'create'])
            ->name('mahasiswa.profile.create');

        Route::post('/mahasiswa/{user}/profile', [MahasiswaController::class, 'store'])
            ->name('mahasiswa.profile.store');

        Route::get('/mahasiswa/{user}/profile', [MahasiswaController::class, 'show'])
            ->name('mahasiswa.profile.show');

        Route::get('/mahasiswa/{user}/profile/edit', [MahasiswaController::class, 'edit'])
            ->name('mahasiswa.profile.edit');

        Route::put('/mahasiswa/{user}/profile', [MahasiswaController::class, 'update'])
            ->name('mahasiswa.profile.update');

        /*
        |--------------------------------------------------------------------------
        | Data Penempatan
        |--------------------------------------------------------------------------
        */
        Route::get('/penempatans', [PenempatanController::class, 'index'])
            ->name('penempatans.index');

        Route::get('/penempatans/create', [PenempatanController::class, 'create'])
            ->name('penempatans.create');

        Route::post('/penempatans', [PenempatanController::class, 'store'])
            ->name('penempatans.store');

        Route::get('/penempatans/{penempatan}', [PenempatanController::class, 'show'])
            ->name('penempatans.show');

        Route::get('/penempatans/{penempatan}/edit', [PenempatanController::class, 'edit'])
            ->name('penempatans.edit');

        Route::put('/penempatans/{penempatan}', [PenempatanController::class, 'update'])
            ->name('penempatans.update');

        Route::put('/penempatans/{penempatan}/toggle-status', [PenempatanController::class, 'toggleStatus'])
            ->name('penempatans.toggle-status');

        /*
        |--------------------------------------------------------------------------
        | Absensi
        |--------------------------------------------------------------------------
        */

        Route::get('/absensi', [AdminAbsensiController::class, 'index'])
            ->name('absensi.index');

        Route::get('/absensi/export', [AdminAbsensiController::class, 'export'])
            ->name('absensi.export');

        Route::get('/absensi/{absensi}', [AdminAbsensiController::class, 'show'])
            ->name('absensi.show');
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

        /*
        |--------------------------------------------------------------------------
        | Absensi
        |--------------------------------------------------------------------------
        */
        Route::get('/absensi', [MentorAbsensiController::class, 'index'])
            ->name('absensi.index');

        Route::get('/absensi/rekap', [MentorAbsensiController::class, 'rekap'])
            ->name('absensi.rekap');

        Route::get('/absensi/{absensi}', [MentorAbsensiController::class, 'show'])
            ->name('absensi.show');

        Route::post('/absensi/{absensi}/approve', [MentorAbsensiController::class, 'approve'])
            ->name('absensi.approve');

        Route::post('/absensi/{absensi}/reject', [MentorAbsensiController::class, 'reject'])
            ->name('absensi.reject');
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

    /*
        |--------------------------------------------------------------------------
        | Absensi
        |--------------------------------------------------------------------------
        */
        Route::get('/absensi', [MahasiswaAbsensiController::class, 'index'])
            ->name('absensi.index');

        Route::get('/absensi/riwayat', [MahasiswaAbsensiController::class, 'riwayat'])
            ->name('absensi.riwayat');

        Route::post('/absensi/masuk', [MahasiswaAbsensiController::class, 'storeMasuk'])
            ->name('absensi.masuk');

        Route::post('/absensi/pulang', [MahasiswaAbsensiController::class, 'storePulang'])
            ->name('absensi.pulang');
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