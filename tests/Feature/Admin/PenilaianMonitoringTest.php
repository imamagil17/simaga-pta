<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\Penilaian;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createAdminPenilaianData(): array
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
        'nip' => 'APN-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'APN-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Admin Penilaian',
        'kode_periode' => 'APN-' . fake()->unique()->numberBetween(1000, 9999),
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

function createOtherAdminPenilaianData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'APN-OTHER-M-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'APN-OTHER-S-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Lain',
        'kode_periode' => 'APN-OTHER-P-' . fake()->unique()->numberBetween(1000, 9999),
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

test('administrator can access assessment monitoring', function () {
    $data = createAdminPenilaianData();

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.penilaian.index'))
        ->assertOk()
        ->assertViewIs('admin.penilaian.index')
        ->assertViewHas('penilaian')
        ->assertViewHas('rekap')
        ->assertViewHas('mentors')
        ->assertViewHas('mahasiswas')
        ->assertViewHas('periodeMagangs')
        ->assertViewHas('filters');
});

test('administrator can see all assessments', function () {
    $data = createAdminPenilaianData();
    $other = createOtherAdminPenilaianData();

    $ownAssessment = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 90,
        'nilai_kehadiran' => 90,
        'nilai_kinerja' => 90,
        'nilai_kompetensi' => 90,
        'nilai_sikap' => 90,
        'nilai_akhir' => 90,
        'status' => 'final',
    ]);

    $otherAssessment = Penilaian::create([
        'mahasiswa_id' => $other['mahasiswa']->id,
        'penempatan_id' => $other['penempatan']->id,
        'mentor_id' => $other['mentor']->id,
        'nilai_kedisiplinan' => 80,
        'nilai_kehadiran' => 80,
        'nilai_kinerja' => 80,
        'nilai_kompetensi' => 80,
        'nilai_sikap' => 80,
        'nilai_akhir' => 80,
        'status' => 'draft',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.penilaian.index'));

    $penilaian = $response->viewData('penilaian');

    expect($penilaian)->toHaveCount(2);

    expect(
        $penilaian
            ->pluck('id')
            ->sort()
            ->values()
            ->all()
    )->toBe(
        collect([
            $ownAssessment->id,
            $otherAssessment->id,
        ])
            ->sort()
            ->values()
            ->all()
    );
});

test('administrator can filter assessment by status', function () {
    $data = createAdminPenilaianData();

    $final = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 90,
        'nilai_kehadiran' => 90,
        'nilai_kinerja' => 90,
        'nilai_kompetensi' => 90,
        'nilai_sikap' => 90,
        'nilai_akhir' => 90,
        'status' => 'final',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Buat penempatan kedua yang benar-benar ada.
    |--------------------------------------------------------------------------
    */
    $otherPeriode = PeriodeMagang::create([
        'nama_periode' => 'Periode Kedua Admin Penilaian',
        'kode_periode' => 'APN-F-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2027-01-01',
        'tanggal_selesai' => '2027-03-31',
        'status' => 'active',
    ]);

    $otherPlacement = Penempatan::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'periode_magang_id' => $otherPeriode->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'active',
    ]);

    Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $otherPlacement->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 80,
        'nilai_kehadiran' => 80,
        'nilai_kinerja' => 80,
        'nilai_kompetensi' => 80,
        'nilai_sikap' => 80,
        'nilai_akhir' => 80,
        'status' => 'draft',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(
            route('admin.penilaian.index', [
                'status' => 'final',
            ])
        );

    $penilaian = $response->viewData('penilaian');

    expect($penilaian)->toHaveCount(1);

    expect($penilaian->first()->id)
        ->toBe($final->id);
});

test('administrator can filter assessment by mentor', function () {
    $data = createAdminPenilaianData();
    $other = createOtherAdminPenilaianData();

    $own = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 90,
        'nilai_kehadiran' => 90,
        'nilai_kinerja' => 90,
        'nilai_kompetensi' => 90,
        'nilai_sikap' => 90,
        'nilai_akhir' => 90,
        'status' => 'final',
    ]);

    Penilaian::create([
        'mahasiswa_id' => $other['mahasiswa']->id,
        'penempatan_id' => $other['penempatan']->id,
        'mentor_id' => $other['mentor']->id,
        'nilai_kedisiplinan' => 80,
        'nilai_kehadiran' => 80,
        'nilai_kinerja' => 80,
        'nilai_kompetensi' => 80,
        'nilai_sikap' => 80,
        'nilai_akhir' => 80,
        'status' => 'final',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(
            route('admin.penilaian.index', [
                'mentor_id' => $data['mentor']->id,
            ])
        );

    $penilaian = $response->viewData('penilaian');

    expect($penilaian)->toHaveCount(1);

    expect($penilaian->first()->id)
        ->toBe($own->id);
});

test('administrator can filter assessment by mahasiswa', function () {
    $data = createAdminPenilaianData();
    $other = createOtherAdminPenilaianData();

    $own = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 90,
        'nilai_kehadiran' => 90,
        'nilai_kinerja' => 90,
        'nilai_kompetensi' => 90,
        'nilai_sikap' => 90,
        'nilai_akhir' => 90,
        'status' => 'final',
    ]);

    Penilaian::create([
        'mahasiswa_id' => $other['mahasiswa']->id,
        'penempatan_id' => $other['penempatan']->id,
        'mentor_id' => $other['mentor']->id,
        'nilai_kedisiplinan' => 80,
        'nilai_kehadiran' => 80,
        'nilai_kinerja' => 80,
        'nilai_kompetensi' => 80,
        'nilai_sikap' => 80,
        'nilai_akhir' => 80,
        'status' => 'final',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(
            route('admin.penilaian.index', [
                'mahasiswa_id' => $data['mahasiswa']->id,
            ])
        );

    $penilaian = $response->viewData('penilaian');

    expect($penilaian)->toHaveCount(1);

    expect($penilaian->first()->id)
        ->toBe($own->id);
});

test('administrator can filter assessment by period', function () {
    $data = createAdminPenilaianData();

    $own = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 90,
        'nilai_kehadiran' => 90,
        'nilai_kinerja' => 90,
        'nilai_kompetensi' => 90,
        'nilai_sikap' => 90,
        'nilai_akhir' => 90,
        'status' => 'final',
    ]);

    $otherPeriode = PeriodeMagang::create([
        'nama_periode' => 'Periode Kedua Filter',
        'kode_periode' => 'APN-P-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2027-01-01',
        'tanggal_selesai' => '2027-03-31',
        'status' => 'active',
    ]);

    $otherPlacement = Penempatan::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'periode_magang_id' => $otherPeriode->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'active',
    ]);

    Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $otherPlacement->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 80,
        'nilai_kehadiran' => 80,
        'nilai_kinerja' => 80,
        'nilai_kompetensi' => 80,
        'nilai_sikap' => 80,
        'nilai_akhir' => 80,
        'status' => 'final',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(
            route('admin.penilaian.index', [
                'periode_id' => $data['periode']->id,
            ])
        );

    $penilaian = $response->viewData('penilaian');

    expect($penilaian)->toHaveCount(1);

    expect($penilaian->first()->id)
        ->toBe($own->id);
});

test('administrator can view assessment detail', function () {
    $data = createAdminPenilaianData();

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
        'status' => 'final',
    ]);

    $this
        ->actingAs($data['admin'])
        ->get(
            route(
                'admin.penilaian.show',
                $penilaian
            )
        )
        ->assertOk()
        ->assertViewIs(
            'admin.penilaian.show'
        )
        ->assertViewHas(
            'penilaian'
        );
});

test('mentor cannot access admin assessment monitoring', function () {
    $data = createAdminPenilaianData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('admin.penilaian.index'))
        ->assertForbidden();
});

test('mahasiswa cannot access admin assessment monitoring', function () {
    $data = createAdminPenilaianData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('admin.penilaian.index'))
        ->assertForbidden();
});
