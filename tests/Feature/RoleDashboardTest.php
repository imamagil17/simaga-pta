<?php

use App\Models\User;

test('guests are redirected to login when accessing dashboards', function () {
    $this->get('/admin/dashboard')->assertRedirect('/login');
    $this->get('/mentor/dashboard')->assertRedirect('/login');
    $this->get('/mahasiswa/dashboard')->assertRedirect('/login');
});

test('administrator can access admin dashboard but not other role dashboards', function () {
    $admin = User::factory()->create(['role' => 'administrator']);

    $this->actingAs($admin)->get('/admin/dashboard')->assertStatus(200);
    $this->actingAs($admin)->get('/mentor/dashboard')->assertStatus(403);
    $this->actingAs($admin)->get('/mahasiswa/dashboard')->assertStatus(403);
});

test('mentor can access mentor dashboard but not other role dashboards', function () {
    $mentor = User::factory()->create(['role' => 'mentor']);

    $this->actingAs($mentor)->get('/mentor/dashboard')->assertStatus(200);
    $this->actingAs($mentor)->get('/admin/dashboard')->assertStatus(403);
    $this->actingAs($mentor)->get('/mahasiswa/dashboard')->assertStatus(403);
});

test('mahasiswa can access mahasiswa dashboard but not other role dashboards', function () {
    $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

    $this->actingAs($mahasiswa)->get('/mahasiswa/dashboard')->assertStatus(200);
    $this->actingAs($mahasiswa)->get('/admin/dashboard')->assertStatus(403);
    $this->actingAs($mahasiswa)->get('/mentor/dashboard')->assertStatus(403);
});

test('generic dashboard route redirects authenticated users to their role dashboard', function () {
    $admin = User::factory()->create(['role' => 'administrator']);
    $mentor = User::factory()->create(['role' => 'mentor']);
    $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

    $this->actingAs($admin)->get('/dashboard')->assertRedirect(route('admin.dashboard'));
    $this->actingAs($mentor)->get('/dashboard')->assertRedirect(route('mentor.dashboard'));
    $this->actingAs($mahasiswa)->get('/dashboard')->assertRedirect(route('mahasiswa.dashboard'));
});
