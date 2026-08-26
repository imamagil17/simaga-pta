<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createAdminLaporanData(): array
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
        'nip' => 'ALP-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'ALP-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Laporan',
        'kode_periode' => 'ALP-' . fake()->unique()->numberBetween(1000, 9999),
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

test('administrator can access report page', function () {
    $data = createAdminLaporanData();

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.laporan.index'))
        ->assertOk()
        ->assertViewIs('admin.laporan.index')
        ->assertViewHas('laporan')
        ->assertViewHas('mahasiswas')
        ->assertViewHas('periodeMagangs')
        ->assertViewHas('filters');
});

test('administrator can generate student report', function () {
    $data = createAdminLaporanData();

    $response = $this
        ->actingAs($data['admin'])
        ->get(
            route('admin.laporan.index', [
                'mahasiswa_id' => $data['mahasiswa']->id,
                'periode_id' => $data['periode']->id,
            ])
        );

    $laporan = $response->viewData('laporan');

    expect($laporan)->toHaveCount(1);

    expect(
        $laporan->first()['mahasiswa']['id']
    )->toBe($data['mahasiswa']->id);
});

test('administrator can view report detail', function () {
    $data = createAdminLaporanData();

    $this
        ->actingAs($data['admin'])
        ->get(
            route(
                'admin.laporan.show',
                $data['penempatan']
            )
        )
        ->assertOk()
        ->assertViewIs('admin.laporan.show')
        ->assertViewHas('laporan');
});

test('administrator can download report pdf', function () {
    $data = createAdminLaporanData();

    $response = $this
        ->actingAs($data['admin'])
        ->get(
            route(
                'admin.laporan.pdf',
                $data['penempatan']
            )
        );

    $response
        ->assertOk()
        ->assertHeader(
            'content-type',
            'application/pdf'
        );
});

test('mentor cannot access admin report page', function () {
    $data = createAdminLaporanData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('admin.laporan.index'))
        ->assertForbidden();
});

test('mahasiswa cannot access admin report page', function () {
    $data = createAdminLaporanData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('admin.laporan.index'))
        ->assertForbidden();
});
