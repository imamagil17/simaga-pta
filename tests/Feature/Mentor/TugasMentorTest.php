<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMentorTugasData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'MTG-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'MTG-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Tugas Mentor',
        'kode_periode' => 'MTG-' . fake()->unique()->numberBetween(1000, 9999),
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

function createOtherMentorTugasData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'OTHER-MTG-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'OTHER-MTG-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mentor Lain',
        'kode_periode' => 'OTHER-MTG-' . fake()->unique()->numberBetween(1000, 9999),
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

test('mentor can access tugas page', function () {
    $data = createMentorTugasData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.tugas.index'))
        ->assertOk()
        ->assertViewIs('mentor.tugas.index')
        ->assertViewHas('tugas')
        ->assertViewHas('rekap');
});

test('mentor sees only tugas for own students', function () {
    $data = createMentorTugasData();
    $other = createOtherMentorTugasData();

    $ownTask = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Saya',
        'deskripsi' => 'Tugas mahasiswa bimbingan saya.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    Tugas::create([
        'penempatan_id' => $other['penempatan']->id,
        'judul' => 'Tugas Mentor Lain',
        'deskripsi' => 'Tugas mahasiswa mentor lain.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $other['mentorUser']->id,
    ]);

    $response = $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.tugas.index'));

    $tugas = $response->viewData('tugas');

    expect($tugas)->toHaveCount(1);
    expect($tugas->first()->id)->toBe($ownTask->id);
});

test('mentor can access create tugas page', function () {
    $data = createMentorTugasData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.tugas.create'))
        ->assertOk()
        ->assertViewIs('mentor.tugas.create')
        ->assertViewHas('penempatans');
});

test('mentor can create draft tugas', function () {
    $data = createMentorTugasData();

    $this
        ->actingAs($data['mentorUser'])
        ->post(route('mentor.tugas.store'), [
            'penempatan_id' => $data['penempatan']->id,
            'judul' => 'Tugas Modul Absensi',
            'deskripsi' => 'Membuat modul absensi pada sistem SIMAGA PTA.',
            'tanggal_mulai' => '2026-08-24 08:00',
            'tanggal_deadline' => '2026-08-28 16:00',
        ])
        ->assertRedirect(route('mentor.tugas.index'))
        ->assertSessionHas('success');

    $tugas = Tugas::first();

    expect($tugas)->not->toBeNull();
    expect($tugas->status)->toBe('draft');
    expect($tugas->created_by)
        ->toBe($data['mentorUser']->id);
});

test('mentor cannot create task for another mentors student', function () {
    $data = createMentorTugasData();
    $other = createOtherMentorTugasData();

    $this
        ->actingAs($data['mentorUser'])
        ->post(route('mentor.tugas.store'), [
            'penempatan_id' => $other['penempatan']->id,
            'judul' => 'Tugas Tidak Sah',
            'deskripsi' => 'Tugas ini seharusnya ditolak.',
            'tanggal_mulai' => '2026-08-24 08:00',
            'tanggal_deadline' => '2026-08-28 16:00',
        ])
        ->assertSessionHasErrors('penempatan_id');

    expect(Tugas::count())->toBe(0);
});

test('mentor can view own task detail', function () {
    $data = createMentorTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Detail Tugas',
        'deskripsi' => 'Deskripsi tugas.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'draft',
        'created_by' => $data['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.tugas.show', $tugas))
        ->assertOk()
        ->assertViewIs('mentor.tugas.show')
        ->assertViewHas('tugas');
});

test('mentor cannot view another mentors task', function () {
    $data = createMentorTugasData();
    $other = createOtherMentorTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $other['penempatan']->id,
        'judul' => 'Tugas Mentor Lain',
        'deskripsi' => 'Tidak boleh diakses.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'draft',
        'created_by' => $other['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.tugas.show', $tugas))
        ->assertForbidden();
});

test('mentor can edit draft task', function () {
    $data = createMentorTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Judul Lama',
        'deskripsi' => 'Deskripsi lama.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'draft',
        'created_by' => $data['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.tugas.edit', $tugas))
        ->assertOk()
        ->assertViewIs('mentor.tugas.edit')
        ->assertViewHas('tugas');
});

test('mentor can update draft task', function () {
    $data = createMentorTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Judul Lama',
        'deskripsi' => 'Deskripsi lama.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'draft',
        'created_by' => $data['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->put(route('mentor.tugas.update', $tugas), [
            'penempatan_id' => $data['penempatan']->id,
            'judul' => 'Judul Baru',
            'deskripsi' => 'Deskripsi tugas yang diperbarui.',
            'tanggal_mulai' => '2026-08-24 08:00',
            'tanggal_deadline' => '2026-08-29 16:00',
        ])
        ->assertRedirect(route('mentor.tugas.show', $tugas))
        ->assertSessionHas('success');

    $tugas->refresh();

    expect($tugas->judul)
        ->toBe('Judul Baru');

    expect($tugas->status)
        ->toBe('draft');
});

test('mentor can publish draft task', function () {
    $data = createMentorTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Publish',
        'deskripsi' => 'Tugas akan dipublikasikan.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'draft',
        'created_by' => $data['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(route('mentor.tugas.publish', $tugas))
        ->assertRedirect(route('mentor.tugas.show', $tugas));

    $tugas->refresh();

    expect($tugas->status)
        ->toBe('published');
});

test('mentor can close published task', function () {
    $data = createMentorTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Close',
        'deskripsi' => 'Tugas akan ditutup.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(route('mentor.tugas.close', $tugas))
        ->assertRedirect(route('mentor.tugas.show', $tugas));

    $tugas->refresh();

    expect($tugas->status)
        ->toBe('closed');
});

test('mentor cannot edit published task', function () {
    $data = createMentorTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Published',
        'deskripsi' => 'Sudah dipublikasikan.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.tugas.edit', $tugas))
        ->assertRedirect(route('mentor.tugas.show', $tugas));
});

test('mahasiswa cannot access mentor task management', function () {
    $data = createMentorTugasData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mentor.tugas.index'))
        ->assertForbidden();
});
