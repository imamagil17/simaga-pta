<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMahasiswaProgressData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'PRG-' . fake()->unique()->numberBetween(100000, 999999),
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'status' => 'active',
    ]);

    $mahasiswaUser = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $mahasiswaUser->id,
        'nim' => 'PRG-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Progress Mahasiswa',
        'kode_periode' => 'PRG-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2026-08-01',
        'tanggal_selesai' => '2026-11-30',
        'status' => 'active',
    ]);

    $penempatan = Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
        'status' => 'active',
    ]);

    return compact(
        'mentorUser',
        'mentor',
        'mahasiswaUser',
        'mahasiswa',
        'periode',
        'penempatan'
    );
}

test('mahasiswa can access progress page', function () {
    $data = createMahasiswaProgressData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.progress.index'))
        ->assertOk()
        ->assertViewIs('mahasiswa.progress.index')
        ->assertViewHas('penempatan')
        ->assertViewHas('progress')
        ->assertViewHas('persentaseKeseluruhan');
});

test('mahasiswa without active placement can access progress page', function () {
    $data = createMahasiswaProgressData();

    $data['penempatan']->update([
        'status' => 'inactive',
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.progress.index'))
        ->assertOk()
        ->assertViewIs('mahasiswa.progress.index')
        ->assertViewHas('penempatan', null);
});

test('mentor cannot access mahasiswa progress page', function () {
    $data = createMahasiswaProgressData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mahasiswa.progress.index'))
        ->assertForbidden();
});

test('another role cannot access mahasiswa progress page', function () {
    $data = createMahasiswaProgressData();

    $admin = User::factory()->create([
        'role' => 'administrator',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $this
        ->actingAs($admin)
        ->get(route('mahasiswa.progress.index'))
        ->assertForbidden();
});
