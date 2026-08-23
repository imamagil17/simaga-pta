<?php

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user hasOne mahasiswa relationship works', function () {
    $user = User::factory()->create([
        'role' => 'mahasiswa',
    ]);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $user->id,
        'nim' => '2026001001',
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'no_hp' => '081234567890',
    ]);

    expect($user->mahasiswa)->not->toBeNull();
    expect($user->mahasiswa->id)->toBe($mahasiswa->id);
    expect($user->mahasiswa->nim)->toBe('2026001001');
    expect($user->mahasiswa->program_studi)->toBe('Teknik Informatika');
});

test('mahasiswa belongsTo user relationship works', function () {
    $user = User::factory()->create([
        'role' => 'mahasiswa',
    ]);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $user->id,
        'nim' => '2026001002',
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
    ]);

    expect($mahasiswa->user)->not->toBeNull();
    expect($mahasiswa->user->id)->toBe($user->id);
    expect($mahasiswa->user->name)->toBe($user->name);
});

test('mahasiswa user_id must be unique', function () {
    $user = User::factory()->create([
        'role' => 'mahasiswa',
    ]);

    Mahasiswa::create([
        'user_id' => $user->id,
        'nim' => '2026001003',
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
    ]);

    expect(function () use ($user) {
        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '2026001004',
            'perguruan_tinggi' => 'Universitas Tadulako',
            'program_studi' => 'Teknik Informatika',
        ]);
    })->toThrow(QueryException::class);
});

test('mahasiswa nim must be unique', function () {
    $user1 = User::factory()->create([
        'role' => 'mahasiswa',
    ]);

    $user2 = User::factory()->create([
        'role' => 'mahasiswa',
    ]);

    Mahasiswa::create([
        'user_id' => $user1->id,
        'nim' => '2026001005',
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
    ]);

    expect(function () use ($user2) {
        Mahasiswa::create([
            'user_id' => $user2->id,
            'nim' => '2026001005',
            'perguruan_tinggi' => 'Universitas Tadulako',
            'program_studi' => 'Teknik Informatika',
        ]);
    })->toThrow(QueryException::class);
});

test('mahasiswa default status is active', function () {
    $user = User::factory()->create([
        'role' => 'mahasiswa',
    ]);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $user->id,
        'nim' => '2026001006',
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
    ]);

    expect($mahasiswa->status)->toBe('active');
});