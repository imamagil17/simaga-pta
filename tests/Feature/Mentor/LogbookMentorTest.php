<?php

use App\Models\Logbook;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMentorLogbookData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'MLG-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'MLG-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mentor Logbook',
        'kode_periode' => 'MLG-' . fake()->unique()->numberBetween(1000, 9999),
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

function createOtherMentorLogbookData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'OTHER-MLG-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'OTHER-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mentor Lain',
        'kode_periode' => 'OTHER-MLG-' . fake()->unique()->numberBetween(1000, 9999),
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

test('mentor can access logbook list', function () {
    $data = createMentorLogbookData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.logbook.index'))
        ->assertOk()
        ->assertViewIs('mentor.logbook.index')
        ->assertViewHas('logbooks')
        ->assertViewHas('rekap');
});

test('mentor sees only logbooks from own students', function () {
    $data = createMentorLogbookData();
    $other = createOtherMentorLogbookData();

    $ownLogbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Logbook Mahasiswa Sendiri',
        'uraian_kegiatan' => 'Kegiatan mahasiswa bimbingan sendiri.',
        'status' => 'submitted',
    ]);

    Logbook::create([
        'penempatan_id' => $other['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Logbook Mahasiswa Lain',
        'uraian_kegiatan' => 'Kegiatan mahasiswa mentor lain.',
        'status' => 'submitted',
    ]);

    $response = $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.logbook.index'));

    $logbooks = $response->viewData('logbooks');

    expect($logbooks)->toHaveCount(1);
    expect($logbooks->first()->id)->toBe($ownLogbook->id);
});

test('mentor can view own student logbook detail', function () {
    $data = createMentorLogbookData();

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Pengembangan Sistem',
        'uraian_kegiatan' => 'Mengerjakan modul sistem informasi.',
        'status' => 'submitted',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.logbook.show', $logbook))
        ->assertOk()
        ->assertViewIs('mentor.logbook.show')
        ->assertViewHas('logbook');
});

test('mentor cannot view another mentors logbook', function () {
    $data = createMentorLogbookData();
    $other = createOtherMentorLogbookData();

    $logbook = Logbook::create([
        'penempatan_id' => $other['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Logbook Mahasiswa Lain',
        'uraian_kegiatan' => 'Kegiatan mentor lain.',
        'status' => 'submitted',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.logbook.show', $logbook))
        ->assertForbidden();
});

test('mentor can approve submitted logbook', function () {
    $data = createMentorLogbookData();

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Pengembangan Sistem',
        'uraian_kegiatan' => 'Mengerjakan modul sistem informasi.',
        'status' => 'submitted',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(route('mentor.logbook.approve', $logbook))
        ->assertRedirect(route('mentor.logbook.show', $logbook))
        ->assertSessionHas('success');

    $logbook->refresh();

    expect($logbook->status)->toBe('approved');

    expect($logbook->reviewed_by)
        ->toBe($data['mentorUser']->id);

    expect($logbook->reviewed_at)
        ->not->toBeNull();
});

test('mentor cannot approve non submitted logbook', function () {
    $data = createMentorLogbookData();

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Draft',
        'uraian_kegiatan' => 'Masih berupa draft.',
        'status' => 'draft',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(route('mentor.logbook.approve', $logbook))
        ->assertRedirect();

    $logbook->refresh();

    expect($logbook->status)->toBe('draft');
});

test('mentor can request revision with note', function () {
    $data = createMentorLogbookData();

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Pengembangan Sistem',
        'uraian_kegiatan' => 'Kegiatan yang perlu diperbaiki.',
        'status' => 'submitted',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(route('mentor.logbook.revision', $logbook), [
            'catatan_mentor' => 'Mohon uraian kegiatan dibuat lebih detail.',
        ])
        ->assertRedirect(route('mentor.logbook.show', $logbook))
        ->assertSessionHas('success');

    $logbook->refresh();

    expect($logbook->status)
        ->toBe('revision');

    expect($logbook->catatan_mentor)
        ->toBe('Mohon uraian kegiatan dibuat lebih detail.');

    expect($logbook->reviewed_by)
        ->toBe($data['mentorUser']->id);

    expect($logbook->reviewed_at)
        ->not->toBeNull();
});

test('mentor cannot request revision without note', function () {
    $data = createMentorLogbookData();

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Pengembangan Sistem',
        'uraian_kegiatan' => 'Kegiatan yang perlu diperbaiki.',
        'status' => 'submitted',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(route('mentor.logbook.revision', $logbook), [
            'catatan_mentor' => '',
        ])
        ->assertSessionHasErrors('catatan_mentor');

    $logbook->refresh();

    expect($logbook->status)
        ->toBe('submitted');
});

test('mentor cannot approve another mentors logbook', function () {
    $data = createMentorLogbookData();
    $other = createOtherMentorLogbookData();

    $logbook = Logbook::create([
        'penempatan_id' => $other['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Logbook Mentor Lain',
        'uraian_kegiatan' => 'Tidak boleh diakses mentor lain.',
        'status' => 'submitted',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(route('mentor.logbook.approve', $logbook))
        ->assertForbidden();

    $logbook->refresh();

    expect($logbook->status)
        ->toBe('submitted');
});

test('mahasiswa cannot access mentor logbook management', function () {
    $data = createMentorLogbookData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mentor.logbook.index'))
        ->assertForbidden();
});
