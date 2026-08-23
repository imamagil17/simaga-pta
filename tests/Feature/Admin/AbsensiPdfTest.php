<?php

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createAdminPdfData(): array
{
    $admin = User::factory()->create([
        'role' => 'administrator',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'PDF-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'PDF-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode PDF',
        'kode_periode' => 'PDF-' . fake()->unique()->numberBetween(1000, 9999),
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
        'admin',
        'mentorUser',
        'mentor',
        'mahasiswaUser',
        'mahasiswa',
        'periode',
        'penempatan'
    );
}

test('administrator can export attendance pdf', function () {
    $data = createAdminPdfData();

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:03',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'menit_terlambat' => 3,
        'status_verifikasi' => 'approved',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.absensi.export-pdf'));

    $response
        ->assertOk()
        ->assertDownload();

    expect($response->headers->get('content-type'))
        ->toContain('application/pdf');
});

test('administrator can export filtered attendance pdf', function () {
    $data = createAdminPdfData();

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:00',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'status_verifikasi' => 'approved',
    ]);

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-25',
        'jam_masuk' => '08:05',
        'status_kehadiran' => 'hadir',
        'menit_terlambat' => 5,
        'status_verifikasi' => 'pending',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.absensi.export-pdf', [
            'status_verifikasi' => 'pending',
        ]));

    $response
        ->assertOk()
        ->assertDownload();

    expect($response->headers->get('content-type'))
        ->toContain('application/pdf');
});

test('mentor cannot export attendance pdf', function () {
    $data = createAdminPdfData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('admin.absensi.export-pdf'))
        ->assertForbidden();
});

test('mahasiswa cannot export attendance pdf', function () {
    $data = createAdminPdfData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('admin.absensi.export-pdf'))
        ->assertForbidden();
});
