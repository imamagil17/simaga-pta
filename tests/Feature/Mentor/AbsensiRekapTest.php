<?php

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMentorRekapData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'REKAP-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'REKAP-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Rekap Mentor',
        'kode_periode' => 'REKAP-' . fake()->unique()->numberBetween(1000, 9999),
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

test('mentor can access attendance recap', function () {
    $data = createMentorRekapData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.absensi.rekap'))
        ->assertOk()
        ->assertViewIs('mentor.absensi.rekap')
        ->assertViewHas('mahasiswaRekap')
        ->assertViewHas('rekap');
});

test('mentor recap calculates attendance correctly', function () {
    $data = createMentorRekapData();

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:03',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'menit_terlambat' => 3,
        'status_verifikasi' => 'approved',
    ]);

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-25',
        'jam_masuk' => '08:00',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'status_verifikasi' => 'pending',
    ]);

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-26',
        'status_kehadiran' => 'izin',
        'status_verifikasi' => 'approved',
    ]);

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-27',
        'status_kehadiran' => 'sakit',
        'status_verifikasi' => 'rejected',
    ]);

    $response = $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.absensi.rekap'));

    $response->assertOk();

    $rekap = $response->viewData('rekap');

    expect($rekap['total_mahasiswa'])->toBe(1);
    expect($rekap['total_absensi'])->toBe(4);
    expect($rekap['hadir'])->toBe(2);
    expect($rekap['izin'])->toBe(1);
    expect($rekap['sakit'])->toBe(1);
    expect($rekap['alpa'])->toBe(0);
    expect($rekap['terlambat'])->toBe(1);
    expect($rekap['total_menit_terlambat'])->toBe(3);
    expect($rekap['approved'])->toBe(2);
    expect($rekap['pending'])->toBe(1);
    expect($rekap['rejected'])->toBe(1);
});

test('mentor cannot access another mentors recap data', function () {
    $data = createMentorRekapData();

    $otherMentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $otherMentor = Mentor::create([
        'user_id' => $otherMentorUser->id,
        'nip' => 'OTHER-REKAP-' . fake()->unique()->numberBetween(100000, 999999),
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

    $otherPenempatan = Penempatan::create([
        'mahasiswa_id' => $otherMahasiswa->id,
        'periode_magang_id' => $data['periode']->id,
        'mentor_id' => $otherMentor->id,
        'status' => 'active',
    ]);

    Absensi::create([
        'penempatan_id' => $otherPenempatan->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:00',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'status_verifikasi' => 'approved',
    ]);

    $response = $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.absensi.rekap'));

    $response->assertOk();

    $mahasiswaRekap = $response->viewData('mahasiswaRekap');

    expect($mahasiswaRekap)->toHaveCount(0);
});

test('mahasiswa cannot access mentor attendance recap', function () {
    $data = createMentorRekapData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mentor.absensi.rekap'))
        ->assertForbidden();
});
