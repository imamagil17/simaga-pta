<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createAdminTugasMonitoringData(): array
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
        'nip' => 'ADM-TGS-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'ADM-TGS-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Monitoring Tugas',
        'kode_periode' => 'ADM-TGS-' . fake()->unique()->numberBetween(1000, 9999),
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

function createOtherAdminMonitoringMentorData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'ADM-OTHER-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'ADM-OTHER-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mentor Lain',
        'kode_periode' => 'ADM-OTHER-' . fake()->unique()->numberBetween(1000, 9999),
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

test('administrator can access tugas monitoring', function () {
    $data = createAdminTugasMonitoringData();

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.tugas.index'))
        ->assertOk()
        ->assertViewIs('admin.tugas.index')
        ->assertViewHas('tugas')
        ->assertViewHas('rekap')
        ->assertViewHas('periodeMagangs')
        ->assertViewHas('mentors')
        ->assertViewHas('filters');
});

test('administrator sees all tasks', function () {
    $data = createAdminTugasMonitoringData();
    $other = createOtherAdminMonitoringMentorData();

    $taskOne = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Mahasiswa A',
        'deskripsi' => 'Deskripsi tugas mahasiswa A.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $taskTwo = Tugas::create([
        'penempatan_id' => $other['penempatan']->id,
        'judul' => 'Tugas Mahasiswa B',
        'deskripsi' => 'Deskripsi tugas mahasiswa B.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-29 16:00:00',
        'status' => 'draft',
        'created_by' => $other['mentorUser']->id,
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.tugas.index'));

    $tugas = $response->viewData('tugas');

    expect($tugas)->toHaveCount(2);

    expect(
        $tugas->pluck('id')->sort()->values()->all()
    )->toBe(
        collect([
            $taskOne->id,
            $taskTwo->id,
        ])->sort()->values()->all()
    );
});

test('administrator can filter task by status', function () {
    $data = createAdminTugasMonitoringData();

    $draft = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Draft',
        'deskripsi' => 'Tugas dalam status draft.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'draft',
        'created_by' => $data['mentorUser']->id,
    ]);

    Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Published',
        'deskripsi' => 'Tugas published.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.tugas.index', [
            'status' => 'draft',
        ]));

    $tugas = $response->viewData('tugas');

    expect($tugas)->toHaveCount(1);
    expect($tugas->first()->id)->toBe($draft->id);
});

test('administrator can filter task by mentor', function () {
    $data = createAdminTugasMonitoringData();
    $other = createOtherAdminMonitoringMentorData();

    $ownTask = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Mentor Pertama',
        'deskripsi' => 'Tugas mentor pertama.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    Tugas::create([
        'penempatan_id' => $other['penempatan']->id,
        'judul' => 'Tugas Mentor Kedua',
        'deskripsi' => 'Tugas mentor kedua.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $other['mentorUser']->id,
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.tugas.index', [
            'mentor_id' => $data['mentor']->id,
        ]));

    $tugas = $response->viewData('tugas');

    expect($tugas)->toHaveCount(1);
    expect($tugas->first()->id)->toBe($ownTask->id);
});

test('administrator can filter task by period', function () {
    $data = createAdminTugasMonitoringData();

    $otherPeriode = PeriodeMagang::create([
        'nama_periode' => 'Periode Kedua',
        'kode_periode' => 'ADM-TGS-P2-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2026-12-01',
        'tanggal_selesai' => '2027-02-28',
        'status' => 'active',
    ]);

    $otherPlacement = Penempatan::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'periode_magang_id' => $otherPeriode->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'active',
    ]);

    $taskOwnPeriod = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Periode Pertama',
        'deskripsi' => 'Tugas periode pertama.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    Tugas::create([
        'penempatan_id' => $otherPlacement->id,
        'judul' => 'Tugas Periode Kedua',
        'deskripsi' => 'Tugas periode kedua.',
        'tanggal_mulai' => '2026-12-01 08:00:00',
        'tanggal_deadline' => '2026-12-05 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.tugas.index', [
            'periode_id' => $data['periode']->id,
        ]));

    $tugas = $response->viewData('tugas');

    expect($tugas)->toHaveCount(1);
    expect($tugas->first()->id)->toBe($taskOwnPeriod->id);
});

test('administrator rekap counts task and submission statuses correctly', function () {
    $data = createAdminTugasMonitoringData();

    $draftTask = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Draft',
        'deskripsi' => 'Draft.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'draft',
        'created_by' => $data['mentorUser']->id,
    ]);

    $publishedTask = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Published',
        'deskripsi' => 'Published.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $closedTask = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Closed',
        'deskripsi' => 'Closed.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'closed',
        'created_by' => $data['mentorUser']->id,
    ]);

    PengumpulanTugas::create([
        'tugas_id' => $publishedTask->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban submitted.',
        'status' => 'submitted',
        'dikumpulkan_at' => now(),
    ]);

    /*
    |--------------------------------------------------------------------------
    | Gunakan mahasiswa yang sama untuk pengumpulan yang berbeda tugas.
    | Setiap tugas hanya mempunyai satu pengumpulan mahasiswa.
    |--------------------------------------------------------------------------
    */
    PengumpulanTugas::create([
        'tugas_id' => $draftTask->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban reviewed.',
        'status' => 'reviewed',
        'nilai' => 85,
        'dikumpulkan_at' => now(),
    ]);

    PengumpulanTugas::create([
        'tugas_id' => $closedTask->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban revision.',
        'status' => 'revision',
        'catatan_mentor' => 'Mohon revisi.',
        'dikumpulkan_at' => now(),
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.tugas.index'));

    $rekap = $response->viewData('rekap');

    expect($rekap['total'])->toBe(3);
    expect($rekap['draft'])->toBe(1);
    expect($rekap['published'])->toBe(1);
    expect($rekap['closed'])->toBe(1);

    expect($rekap['submitted'])->toBe(1);
    expect($rekap['reviewed'])->toBe(1);
    expect($rekap['revision'])->toBe(1);
});

test('administrator can view task detail', function () {
    $data = createAdminTugasMonitoringData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Detail Tugas Admin',
        'deskripsi' => 'Deskripsi detail tugas admin.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.tugas.show', $tugas))
        ->assertOk()
        ->assertViewIs('admin.tugas.show')
        ->assertViewHas('tugas');
});

test('mentor cannot access admin task monitoring', function () {
    $data = createAdminTugasMonitoringData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('admin.tugas.index'))
        ->assertForbidden();
});

test('mahasiswa cannot access admin task monitoring', function () {
    $data = createAdminTugasMonitoringData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('admin.tugas.index'))
        ->assertForbidden();
});
