<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('administrator user redirects to admin dashboard on login', function () {
    $admin = User::factory()->create(['role' => 'administrator']);

    $response = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('admin.dashboard', absolute: false));
});

test('mentor user redirects to mentor dashboard on login', function () {
    $mentor = User::factory()->create(['role' => 'mentor']);

    $response = $this->post('/login', [
        'email' => $mentor->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('mentor.dashboard', absolute: false));
});

test('mahasiswa user redirects to mahasiswa dashboard on login', function () {
    $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

    $response = $this->post('/login', [
        'email' => $mahasiswa->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('mahasiswa.dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
