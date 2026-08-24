<?php

use App\Models\Logbook;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createAdminLogbookData(): array
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
        'nip' => 'ALG-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'ALG-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Admin Logbook',
        'kode_periode' => 'ALG-' . fake()->unique()->numberBetween(1000, 9999),
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

test('administrator can access logbook monitoring', function () {
    $data = createAdminLogbookData();

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.logbook.index'))
        ->assertOk()
        ->assertViewIs('admin.logbook.index')
        ->assertViewHas('logbooks')
        ->assertViewHas('rekap')
        ->assertViewHas('periodeMagangs')
        ->assertViewHas('mentors')
        ->assertViewHas('filters');
});

test('administrator sees correct logbook recap', function () {
    $data = createAdminLogbookData();

    Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Kegiatan Draft',
        'uraian_kegiatan' => 'Kegiatan draft.',
        'status' => 'draft',
    ]);

    Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-25',
        'judul_kegiatan' => 'Kegiatan Submit',
        'uraian_kegiatan' => 'Kegiatan submit.',
        'status' => 'submitted',
    ]);

    Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-26',
        'judul_kegiatan' => 'Kegiatan Approved',
        'uraian_kegiatan' => 'Kegiatan approved.',
        'status' => 'approved',
    ]);

    Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-27',
        'judul_kegiatan' => 'Kegiatan Revision',
        'uraian_kegiatan' => 'Kegiatan revision.',
        'status' => 'revision',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.logbook.index'));

    $rekap = $response->viewData('rekap');

    expect($rekap['total'])->toBe(4);
    expect($rekap['draft'])->toBe(1);
    expect($rekap['submitted'])->toBe(1);
    expect($rekap['approved'])->toBe(1);
    expect($rekap['revision'])->toBe(1);
});

test('administrator can filter logbook by status', function () {
    $data = createAdminLogbookData();

    $submitted = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Submitted',
        'uraian_kegiatan' => 'Logbook submitted.',
        'status' => 'submitted',
    ]);

    Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-25',
        'judul_kegiatan' => 'Approved',
        'uraian_kegiatan' => 'Logbook approved.',
        'status' => 'approved',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.logbook.index', [
            'status' => 'submitted',
        ]));

    $logbooks = $response->viewData('logbooks');

    expect($logbooks)->toHaveCount(1);
    expect($logbooks->first()->id)->toBe($submitted->id);
});

test('administrator can filter logbook by mentor', function () {
    $data = createAdminLogbookData();

    $otherMentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $otherMentor = Mentor::create([
        'user_id' => $otherMentorUser->id,
        'nip' => 'OTHER-ALG-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'OTHER-ALG-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $otherPlacement = Penempatan::create([
        'mahasiswa_id' => $otherMahasiswa->id,
        'periode_magang_id' => $data['periode']->id,
        'mentor_id' => $otherMentor->id,
        'status' => 'active',
    ]);

    $ownLogbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Logbook Mentor A',
        'uraian_kegiatan' => 'Kegiatan mentor A.',
        'status' => 'submitted',
    ]);

    Logbook::create([
        'penempatan_id' => $otherPlacement->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Logbook Mentor B',
        'uraian_kegiatan' => 'Kegiatan mentor B.',
        'status' => 'submitted',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.logbook.index', [
            'mentor_id' => $data['mentor']->id,
        ]));

    $logbooks = $response->viewData('logbooks');

    expect($logbooks)->toHaveCount(1);
    expect($logbooks->first()->id)->toBe($ownLogbook->id);
});

test('administrator can filter logbook by date', function () {
    $data = createAdminLogbookData();

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Logbook Tanggal Tertentu',
        'uraian_kegiatan' => 'Kegiatan pada tanggal tertentu.',
        'status' => 'submitted',
    ]);

    Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-25',
        'judul_kegiatan' => 'Logbook Besok',
        'uraian_kegiatan' => 'Kegiatan besok.',
        'status' => 'submitted',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.logbook.index', [
            'tanggal' => '2026-08-24',
        ]));

    $logbooks = $response->viewData('logbooks');

    expect($logbooks)->toHaveCount(1);
    expect($logbooks->first()->id)->toBe($logbook->id);
});

test('administrator can view logbook detail', function () {
    $data = createAdminLogbookData();

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Detail Logbook',
        'uraian_kegiatan' => 'Isi detail logbook.',
        'hasil_kegiatan' => 'Hasil kegiatan.',
        'kendala' => 'Tidak ada kendala.',
        'rencana_tindak_lanjut' => 'Lanjut kegiatan.',
        'status' => 'approved',
    ]);

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.logbook.show', $logbook))
        ->assertOk()
        ->assertViewIs('admin.logbook.show')
        ->assertViewHas('logbook');
});

test('mentor cannot access administrator logbook monitoring', function () {
    $data = createAdminLogbookData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('admin.logbook.index'))
        ->assertForbidden();
});

test('mahasiswa cannot access administrator logbook monitoring', function () {
    $data = createAdminLogbookData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('admin.logbook.index'))
        ->assertForbidden();
});
