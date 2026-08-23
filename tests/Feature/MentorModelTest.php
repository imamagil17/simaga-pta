<?php

use App\Models\Mentor;
use App\Models\User;
use Illuminate\Database\QueryException;

test('user hasOne mentor relationship works', function () {
    $user = User::factory()->create(['role' => 'mentor']);

    $mentor = Mentor::create([
        'user_id' => $user->id,
        'nip' => '198501012010011001',
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'no_hp' => '081234567890',
        'status' => 'active',
    ]);

    expect($user->mentor)->not->toBeNull();
    expect($user->mentor->id)->toBe($mentor->id);
    expect($user->mentor->nip)->toBe('198501012010011001');
    expect($user->mentor->jabatan)->toBe('Hakim Tinggi');
});

test('mentor belongsTo user relationship works', function () {
    $user = User::factory()->create(['role' => 'mentor']);

    $mentor = Mentor::create([
        'user_id' => $user->id,
        'nip' => '199002022015022002',
        'jabatan' => 'Panitera Pengganti',
        'bagian' => 'Kepaniteraan',
        'jenis_kelamin' => 'Perempuan',
        'agama' => 'Islam',
    ]);

    expect($mentor->user)->not->toBeNull();
    expect($mentor->user->id)->toBe($user->id);
    expect($mentor->user->name)->toBe($user->name);
});

test('mentor user_id must be unique', function () {
    $user = User::factory()->create(['role' => 'mentor']);

    Mentor::create([
        'user_id' => $user->id,
        'nip' => '11111',
    ]);

    expect(function () use ($user) {
        Mentor::create([
            'user_id' => $user->id,
            'nip' => '22222',
        ]);
    })->toThrow(QueryException::class);
});

test('mentor default status is active', function () {
    $user = User::factory()->create(['role' => 'mentor']);

    $mentor = Mentor::create([
        'user_id' => $user->id,
    ]);

    expect($mentor->status)->toBe('active');
});
