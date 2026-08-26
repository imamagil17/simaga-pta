<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\Sertifikat;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMahasiswaSertifikatData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'MSR-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'MSR-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Sertifikat Mahasiswa',
        'kode_periode' => 'MSR-' . fake()->unique()->numberBetween(1000, 9999),
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

test('mahasiswa can access certificate page', function () {
    $data = createMahasiswaSertifikatData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.sertifikat.index'))
        ->assertOk()
        ->assertViewIs('mahasiswa.sertifikat.index')
        ->assertViewHas('sertifikat');
});

test('mahasiswa sees unavailable state when certificate is not approved', function () {
    $data = createMahasiswaSertifikatData();

    Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'pending',
    ]);

    $response = $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.sertifikat.index'));

    $sertifikat = $response->viewData('sertifikat');

    expect($sertifikat)->toBeNull();

    $response
        ->assertSee('Sertifikat Belum Tersedia');
});

test('mahasiswa can see approved certificate', function () {
    $data = createMahasiswaSertifikatData();

    Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'approved',
        'nomor_sertifikat' =>
        '001/SIMAGA/PTA-PALU/VIII/2026',
        'approved_by' => $data['mentorUser']->id,
        'approved_at' => now(),
    ]);

    $response = $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.sertifikat.index'));

    $response
        ->assertOk()
        ->assertSee(
            '001/SIMAGA/PTA-PALU/VIII/2026'
        )
        ->assertSee('Download PDF')
        ->assertSee('Lihat Sertifikat');
});

test('mahasiswa can view approved certificate detail', function () {
    $data = createMahasiswaSertifikatData();

    Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'approved',
        'nomor_sertifikat' =>
        '001/SIMAGA/PTA-PALU/VIII/2026',
        'approved_by' => $data['mentorUser']->id,
        'approved_at' => now(),
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.sertifikat.show'))
        ->assertOk()
        ->assertViewIs('mahasiswa.sertifikat.show')
        ->assertViewHas('sertifikat')
        ->assertSee(
            '001/SIMAGA/PTA-PALU/VIII/2026'
        );
});

test('mahasiswa cannot download certificate before approval', function () {
    $data = createMahasiswaSertifikatData();

    Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'pending',
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.sertifikat.download'))
        ->assertNotFound();
});

test('mahasiswa can download approved certificate', function () {
    $data = createMahasiswaSertifikatData();

    Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'approved',
        'nomor_sertifikat' =>
        '001/SIMAGA/PTA-PALU/VIII/2026',
        'approved_by' => $data['mentorUser']->id,
        'approved_at' => now(),
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.sertifikat.download'))
        ->assertOk()
        ->assertHeader(
            'content-type',
            'application/pdf'
        );
});

test('mahasiswa cannot access another students certificate', function () {
    $data = createMahasiswaSertifikatData();

    $otherUser = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $otherMahasiswa = Mahasiswa::create([
        'user_id' => $otherUser->id,
        'nim' => 'MSR-OTHER-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $otherPeriode = PeriodeMagang::create([
        'nama_periode' => 'Periode Lain',
        'kode_periode' => 'MSR-OTHER-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2026-08-01',
        'tanggal_selesai' => '2026-11-30',
        'status' => 'active',
    ]);

    $otherPenempatan = Penempatan::create([
        'mahasiswa_id' => $otherMahasiswa->id,
        'periode_magang_id' => $otherPeriode->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'active',
    ]);

    Sertifikat::create([
        'mahasiswa_id' => $otherMahasiswa->id,
        'penempatan_id' => $otherPenempatan->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'approved',
        'nomor_sertifikat' =>
        '999/SIMAGA/PTA-PALU/VIII/2026',
        'approved_by' => $data['mentorUser']->id,
        'approved_at' => now(),
    ]);

    $response = $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.sertifikat.index'));

    $sertifikat = $response->viewData('sertifikat');

    expect($sertifikat)->toBeNull();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.sertifikat.download'))
        ->assertNotFound();
});

test('mentor cannot access mahasiswa certificate page', function () {
    $data = createMahasiswaSertifikatData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mahasiswa.sertifikat.index'))
        ->assertForbidden();
});
