<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createAdminSertifikatData(): array
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
        'nip' => 'ASN-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'ASN-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Admin Sertifikat',
        'kode_periode' => 'ASN-P-' . fake()->unique()->numberBetween(1000, 9999),
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

test('administrator can access certificate management', function () {
    $data = createAdminSertifikatData();

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.sertifikat.index'))
        ->assertOk()
        ->assertViewIs('admin.sertifikat.index')
        ->assertViewHas('sertifikats')
        ->assertViewHas('rekap')
        ->assertViewHas('filters');
});

test('administrator can view certificate request detail', function () {
    $data = createAdminSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'pending',
    ]);

    $this
        ->actingAs($data['admin'])
        ->get(
            route(
                'admin.sertifikat.show',
                $sertifikat
            )
        )
        ->assertOk()
        ->assertViewIs('admin.sertifikat.show')
        ->assertViewHas('sertifikat');
});

test('administrator can approve certificate request', function () {
    $data = createAdminSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'pending',
    ]);

    $this
        ->actingAs($data['admin'])
        ->post(
            route(
                'admin.sertifikat.approve',
                $sertifikat
            )
        )
        ->assertRedirect(
            route(
                'admin.sertifikat.show',
                $sertifikat
            )
        )
        ->assertSessionHas(
            'success',
            'Pengajuan sertifikat berhasil disetujui.'
        );

    $sertifikat->refresh();

    expect($sertifikat->status)
        ->toBe('approved');

    expect($sertifikat->approved_by)
        ->toBe($data['admin']->id);

    expect($sertifikat->approved_at)
        ->not->toBeNull();

    expect($sertifikat->nomor_sertifikat)
        ->not->toBeNull();

    expect($sertifikat->nomor_sertifikat)
        ->toContain(
            '/SIMAGA/PTA-PALU/'
        );
});

test('administrator can reject certificate request', function () {
    $data = createAdminSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'pending',
    ]);

    $this
        ->actingAs($data['admin'])
        ->post(
            route(
                'admin.sertifikat.reject',
                $sertifikat
            ),
            [
                'catatan' =>
                'Data mahasiswa belum lengkap.',
            ]
        )
        ->assertRedirect(
            route(
                'admin.sertifikat.show',
                $sertifikat
            )
        )
        ->assertSessionHas(
            'success',
            'Pengajuan sertifikat ditolak.'
        );

    $sertifikat->refresh();

    expect($sertifikat->status)
        ->toBe('rejected');

    expect($sertifikat->catatan)
        ->toBe(
            'Data mahasiswa belum lengkap.'
        );

    expect($sertifikat->approved_by)
        ->toBe($data['admin']->id);

    expect($sertifikat->approved_at)
        ->not->toBeNull();
});

test('administrator cannot approve already approved certificate', function () {
    $data = createAdminSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'approved',
        'nomor_sertifikat' =>
        '001/SIMAGA/PTA-PALU/VIII/2026',
        'approved_by' => $data['admin']->id,
        'approved_at' => now(),
    ]);

    $this
        ->actingAs($data['admin'])
        ->post(
            route(
                'admin.sertifikat.approve',
                $sertifikat
            )
        )
        ->assertSessionHasErrors(
            'sertifikat'
        );

    $sertifikat->refresh();

    expect($sertifikat->status)
        ->toBe('approved');
});

test('administrator cannot reject approved certificate', function () {
    $data = createAdminSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'approved',
        'nomor_sertifikat' =>
        '001/SIMAGA/PTA-PALU/VIII/2026',
        'approved_by' => $data['admin']->id,
        'approved_at' => now(),
    ]);

    $this
        ->actingAs($data['admin'])
        ->post(
            route(
                'admin.sertifikat.reject',
                $sertifikat
            ),
            [
                'catatan' =>
                'Seharusnya tidak dapat ditolak.',
            ]
        )
        ->assertSessionHasErrors(
            'sertifikat'
        );

    $sertifikat->refresh();

    expect($sertifikat->status)
        ->toBe('approved');
});

test('reject certificate requires note', function () {
    $data = createAdminSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'pending',
    ]);

    $this
        ->actingAs($data['admin'])
        ->post(
            route(
                'admin.sertifikat.reject',
                $sertifikat
            ),
            [
                'catatan' => '',
            ]
        )
        ->assertSessionHasErrors(
            'catatan'
        );

    $sertifikat->refresh();

    expect($sertifikat->status)
        ->toBe('pending');
});

test('mentor cannot access admin certificate management', function () {
    $data = createAdminSertifikatData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('admin.sertifikat.index'))
        ->assertForbidden();
});

test('mahasiswa cannot access admin certificate management', function () {
    $data = createAdminSertifikatData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('admin.sertifikat.index'))
        ->assertForbidden();
});
