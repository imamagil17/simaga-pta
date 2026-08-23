<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMahasiswaForPenempatanTest(): Mahasiswa
{
    $user = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    return Mahasiswa::create([
        'user_id' => $user->id,
        'nim' => 'MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'no_hp' => '081234567890',
        'alamat' => 'Palu',
        'status' => 'active',
    ]);
}

function createMentorForPenempatanTest(): Mentor
{
    $user = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    return Mentor::create([
        'user_id' => $user->id,
        'nip' => 'NIP-' . fake()->unique()->numberBetween(100000, 999999),
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'no_hp' => '081234567890',
        'status' => 'active',
    ]);
}

function createPeriodeForPenempatanTest(): PeriodeMagang
{
    return PeriodeMagang::create([
        'nama_periode' => 'Magang Testing',
        'kode_periode' => 'TEST-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2026-08-01',
        'tanggal_selesai' => '2026-11-30',
        'status' => 'active',
        'keterangan' => 'Data testing.',
    ]);
}

test('penempatan belongsTo mahasiswa', function () {
    $mahasiswa = createMahasiswaForPenempatanTest();
    $mentor = createMentorForPenempatanTest();
    $periode = createPeriodeForPenempatanTest();

    $penempatan = Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
    ]);

    expect($penempatan->mahasiswa)->not->toBeNull()
        ->and($penempatan->mahasiswa->id)->toBe($mahasiswa->id);
});

test('penempatan belongsTo periode magang', function () {
    $mahasiswa = createMahasiswaForPenempatanTest();
    $mentor = createMentorForPenempatanTest();
    $periode = createPeriodeForPenempatanTest();

    $penempatan = Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
    ]);

    expect($penempatan->periodeMagang)->not->toBeNull()
        ->and($penempatan->periodeMagang->id)->toBe($periode->id);
});

test('penempatan belongsTo mentor', function () {
    $mahasiswa = createMahasiswaForPenempatanTest();
    $mentor = createMentorForPenempatanTest();
    $periode = createPeriodeForPenempatanTest();

    $penempatan = Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
    ]);

    expect($penempatan->mentor)->not->toBeNull()
        ->and($penempatan->mentor->id)->toBe($mentor->id);
});

test('mahasiswa hasMany penempatan', function () {
    $mahasiswa = createMahasiswaForPenempatanTest();
    $mentor = createMentorForPenempatanTest();
    $periode = createPeriodeForPenempatanTest();

    $penempatan = Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
    ]);

    expect($mahasiswa->penempatans)->toHaveCount(1);
    expect($mahasiswa->penempatans->first()->id)->toBe($penempatan->id);
});

test('periode magang hasMany penempatan', function () {
    $mahasiswa = createMahasiswaForPenempatanTest();
    $mentor = createMentorForPenempatanTest();
    $periode = createPeriodeForPenempatanTest();

    $penempatan = Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
    ]);

    expect($periode->penempatans)->toHaveCount(1);
    expect($periode->penempatans->first()->id)->toBe($penempatan->id);
});

test('mentor hasMany penempatan', function () {
    $mahasiswa = createMahasiswaForPenempatanTest();
    $mentor = createMentorForPenempatanTest();
    $periode = createPeriodeForPenempatanTest();

    $penempatan = Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
    ]);

    expect($mentor->penempatans)->toHaveCount(1);
    expect($mentor->penempatans->first()->id)->toBe($penempatan->id);
});

test('penempatan default status is active', function () {
    $mahasiswa = createMahasiswaForPenempatanTest();
    $mentor = createMentorForPenempatanTest();
    $periode = createPeriodeForPenempatanTest();

    $penempatan = Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
    ]);

    expect($penempatan->status)->toBe('active');
});

test('same mahasiswa cannot have two placements in same period', function () {
    $mahasiswa = createMahasiswaForPenempatanTest();
    $mentor = createMentorForPenempatanTest();
    $periode = createPeriodeForPenempatanTest();

    Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
    ]);

    expect(function () use ($mahasiswa, $mentor, $periode) {
        Penempatan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'periode_magang_id' => $periode->id,
            'mentor_id' => $mentor->id,
        ]);
    })->toThrow(QueryException::class);
});

test('same mahasiswa can have placements in different periods', function () {
    $mahasiswa = createMahasiswaForPenempatanTest();
    $mentor = createMentorForPenempatanTest();

    $periodeA = createPeriodeForPenempatanTest();

    $periodeB = PeriodeMagang::create([
        'nama_periode' => 'Magang Testing Kedua',
        'kode_periode' => 'TEST-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2027-01-01',
        'tanggal_selesai' => '2027-04-30',
        'status' => 'active',
    ]);

    Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periodeA->id,
        'mentor_id' => $mentor->id,
    ]);

    Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periodeB->id,
        'mentor_id' => $mentor->id,
    ]);

    expect(
        Penempatan::where('mahasiswa_id', $mahasiswa->id)->count()
    )->toBe(2);
});