<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\Penilaian;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMahasiswaPenilaianData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'MPN-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'MPN-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Nilai Mahasiswa',
        'kode_periode' => 'MPN-' . fake()->unique()->numberBetween(1000, 9999),
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

test('mahasiswa can access assessment page', function () {
    $data = createMahasiswaPenilaianData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.penilaian.index'))
        ->assertOk()
        ->assertViewIs('mahasiswa.penilaian.index')
        ->assertViewHas('penilaian');
});

test('mahasiswa sees no assessment when mentor has not created one', function () {
    $data = createMahasiswaPenilaianData();

    $response = $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.penilaian.index'));

    $penilaian = $response->viewData('penilaian');

    expect($penilaian)->toBeNull();
});

test('mahasiswa cannot see draft assessment', function () {
    $data = createMahasiswaPenilaianData();

    Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 90,
        'nilai_kehadiran' => 90,
        'nilai_kinerja' => 90,
        'nilai_kompetensi' => 90,
        'nilai_sikap' => 90,
        'nilai_akhir' => 90,
        'status' => 'draft',
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.penilaian.index'))
        ->assertOk()
        ->assertSee('Penilaian Masih Dalam Proses')
        ->assertDontSee('90.00');
});

test('mahasiswa can see finalized assessment', function () {
    $data = createMahasiswaPenilaianData();

    Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 90,
        'nilai_kehadiran' => 95,
        'nilai_kinerja' => 85,
        'nilai_kompetensi' => 88,
        'nilai_sikap' => 92,
        'nilai_akhir' => 90,
        'status' => 'final',
        'finalized_by' => $data['mentorUser']->id,
        'finalized_at' => now(),
    ]);

    $response = $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.penilaian.index'));

    $response
        ->assertOk()
        ->assertSee('90.00')
        ->assertSee('Lihat Detail');
});

test('mahasiswa can view finalized assessment detail', function () {
    $data = createMahasiswaPenilaianData();

    $penilaian = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 90,
        'nilai_kehadiran' => 95,
        'nilai_kinerja' => 85,
        'nilai_kompetensi' => 88,
        'nilai_sikap' => 92,
        'nilai_akhir' => 90,
        'catatan' => 'Kinerja sangat baik.',
        'status' => 'final',
        'finalized_by' => $data['mentorUser']->id,
        'finalized_at' => now(),
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.penilaian.show'))
        ->assertOk()
        ->assertViewIs('mahasiswa.penilaian.show')
        ->assertViewHas('penilaian')
        ->assertSee('90.00')
        ->assertSee('Kinerja sangat baik.');
});

test('mahasiswa cannot access another students assessment', function () {
    $data = createMahasiswaPenilaianData();

    $otherUser = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $otherMahasiswa = Mahasiswa::create([
        'user_id' => $otherUser->id,
        'nim' => 'MPN-OTHER-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $otherPeriode = PeriodeMagang::create([
        'nama_periode' => 'Periode Lain',
        'kode_periode' => 'MPN-OTHER-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2026-08-01',
        'tanggal_selesai' => '2026-11-30',
        'status' => 'active',
    ]);

    $otherPlacement = Penempatan::create([
        'mahasiswa_id' => $otherMahasiswa->id,
        'periode_magang_id' => $otherPeriode->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'active',
    ]);

    Penilaian::create([
        'mahasiswa_id' => $otherMahasiswa->id,
        'penempatan_id' => $otherPlacement->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 100,
        'nilai_kehadiran' => 100,
        'nilai_kinerja' => 100,
        'nilai_kompetensi' => 100,
        'nilai_sikap' => 100,
        'nilai_akhir' => 100,
        'status' => 'final',
        'finalized_by' => $data['mentorUser']->id,
        'finalized_at' => now(),
    ]);

    $response = $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.penilaian.index'));

    $penilaian = $response->viewData('penilaian');

    expect($penilaian)->toBeNull();
});

test('mentor cannot access mahasiswa assessment page', function () {
    $data = createMahasiswaPenilaianData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mahasiswa.penilaian.index'))
        ->assertForbidden();
});
