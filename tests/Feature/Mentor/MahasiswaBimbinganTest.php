<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMahasiswaBimbinganData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'MBB-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'MBB-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mahasiswa Bimbingan',
        'kode_periode' => 'MBB-' . fake()->unique()->numberBetween(1000, 9999),
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

function createOtherMentorBimbinganData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'MBB-OTHER-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'MBB-OTHER-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mentor Lain',
        'kode_periode' => 'MBB-OTHER-P-' . fake()->unique()->numberBetween(1000, 9999),
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

test('mentor can access mahasiswa bimbingan page', function () {
    $data = createMahasiswaBimbinganData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.mahasiswa-bimbingan.index'))
        ->assertOk()
        ->assertViewIs('mentor.mahasiswa-bimbingan.index')
        ->assertViewHas('penempatans')
        ->assertViewHas('rekap');
});

test('mentor sees only own students on bimbingan page', function () {
    $data = createMahasiswaBimbinganData();
    $other = createOtherMentorBimbinganData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.mahasiswa-bimbingan.index'))
        ->assertSee($data['mahasiswaUser']->name)
        ->assertDontSee($other['mahasiswaUser']->name);
});

test('mentor can view own student detail', function () {
    $data = createMahasiswaBimbinganData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(
            route(
                'mentor.mahasiswa-bimbingan.show',
                $data['mahasiswa']
            )
        )
        ->assertOk()
        ->assertViewIs('mentor.mahasiswa-bimbingan.show')
        ->assertViewHas('penempatan')
        ->assertViewHas('absensi')
        ->assertViewHas('logbook')
        ->assertViewHas('tugas')
        ->assertViewHas('penilaian')
        ->assertViewHas('sertifikat');
});

test('mentor cannot view another mentors student detail', function () {
    $data = createMahasiswaBimbinganData();
    $other = createOtherMentorBimbinganData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(
            route(
                'mentor.mahasiswa-bimbingan.show',
                $other['mahasiswa']
            )
        )
        ->assertForbidden();
});

test('mahasiswa cannot access mentor mahasiswa bimbingan page', function () {
    $data = createMahasiswaBimbinganData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mentor.mahasiswa-bimbingan.index'))
        ->assertForbidden();
});

test('administrator cannot access mentor mahasiswa bimbingan page', function () {
    $data = createMahasiswaBimbinganData();

    $admin = User::factory()->create([
        'role' => 'administrator',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $this
        ->actingAs($admin)
        ->get(route('mentor.mahasiswa-bimbingan.index'))
        ->assertForbidden();
});
