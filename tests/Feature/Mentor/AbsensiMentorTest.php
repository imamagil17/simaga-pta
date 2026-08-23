<?php

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMentorAbsensiData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'MENTOR-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mentor Testing',
        'kode_periode' => 'MT-' . fake()->unique()->numberBetween(1000, 9999),
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

test('mentor can access absensi page', function () {
    $data = createMentorAbsensiData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.absensi.index'))
        ->assertOk()
        ->assertViewIs('mentor.absensi.index')
        ->assertViewHas('absensis')
        ->assertViewHas('pendingCount');
});

test('mentor sees absensi from assigned mahasiswa', function () {
    $data = createMentorAbsensiData();

    $absensi = Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-23',
        'jam_masuk' => '08:05',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'menit_terlambat' => 5,
        'status_verifikasi' => 'pending',
        'paraf_mahasiswa' => 'absensi/paraf/mahasiswa/test.png',
        'paraf_mahasiswa_at' => now(),
    ]);

    $response = $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.absensi.index'));

    $response
        ->assertOk()
        ->assertSee($data['mahasiswaUser']->name)
        ->assertSee('Menunggu');

    expect($response->viewData('pendingCount'))->toBe(1);
});

test('mentor does not see another mentors absensi', function () {
    $data = createMentorAbsensiData();

    $otherMentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $otherMentor = Mentor::create([
        'user_id' => $otherMentorUser->id,
        'nip' => 'OTHER-' . fake()->unique()->numberBetween(100000, 999999),
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'status' => 'active',
    ]);

    $otherMahasiswaUser = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $otherMahasiswa = Mahasiswa::create([
        'user_id' => $otherMahasiswaUser->id,
        'nim' => 'OTHER-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $penempatanLain = Penempatan::create([
        'mahasiswa_id' => $otherMahasiswa->id,
        'periode_magang_id' => $data['periode']->id,
        'mentor_id' => $otherMentor->id,
        'status' => 'active',
    ]);

    Absensi::create([
        'penempatan_id' => $penempatanLain->id,
        'tanggal' => '2026-08-23',
        'jam_masuk' => '08:00',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'status_verifikasi' => 'pending',
    ]);

    $response = $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.absensi.index'));

    $response
        ->assertOk()
        ->assertDontSee($otherMahasiswaUser->name);
});

test('mahasiswa cannot access mentor absensi page', function () {
    $data = createMentorAbsensiData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mentor.absensi.index'))
        ->assertForbidden();
});