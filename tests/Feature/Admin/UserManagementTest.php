<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Access
|--------------------------------------------------------------------------
*/

test('administrator can access user management page', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $response = $this
        ->actingAs($admin)
        ->get(route('admin.users.index'));

    $response
        ->assertOk()
        ->assertViewIs('admin.users.index')
        ->assertViewHas('users');
});

test('mentor cannot access user management page', function () {
    $mentor = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $this
        ->actingAs($mentor)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

test('mahasiswa cannot access user management page', function () {
    $mahasiswa = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $this
        ->actingAs($mahasiswa)
        ->get(route('admin.users.index'))
        ->assertForbidden();
});

/*
|--------------------------------------------------------------------------
| Toggle Status
|--------------------------------------------------------------------------
*/

test('administrator can deactivate another user', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $user = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $response = $this
        ->actingAs($admin)
        ->put(route('admin.users.toggle-status', $user));

    $response
        ->assertRedirect()
        ->assertSessionHas(
            'success',
            'Akun pengguna berhasil dinonaktifkan.'
        );

    expect($user->fresh()->status)->toBe('inactive');
});

test('administrator can reactivate inactive user', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $user = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'inactive',
        'must_change_password' => false,
    ]);

    $response = $this
        ->actingAs($admin)
        ->put(route('admin.users.toggle-status', $user));

    $response
        ->assertRedirect()
        ->assertSessionHas(
            'success',
            'Akun pengguna berhasil diaktifkan.'
        );

    expect($user->fresh()->status)->toBe('active');
});

test('administrator cannot deactivate own account', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $this
        ->actingAs($admin)
        ->put(route('admin.users.toggle-status', $admin))
        ->assertRedirect()
        ->assertSessionHasErrors('status');

    expect($admin->fresh()->status)->toBe('active');
});

test('non administrator cannot toggle user status', function () {
    $mentor = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mahasiswa = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $this
        ->actingAs($mentor)
        ->put(route('admin.users.toggle-status', $mahasiswa))
        ->assertForbidden();

    expect($mahasiswa->fresh()->status)->toBe('active');
});