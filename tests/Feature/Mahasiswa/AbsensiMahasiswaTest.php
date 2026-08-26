<?php

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Reset Carbon setelah setiap test.
|--------------------------------------------------------------------------
*/
afterEach(function () {
    Carbon::setTestNow();
});

function createMahasiswaAbsensiUser(): array
{
    $user = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $user->id,
        'nim' => 'MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'status' => 'active',
    ]);

    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'NIP-' . fake()->unique()->numberBetween(100000, 999999),
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'status' => 'active',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Gunakan tanggal tetap.
    |--------------------------------------------------------------------------
    |
    | Jangan menggunakan now() di sini karena beberapa test
    | mengubah Carbon::setTestNow() setelah data dibuat.
    |
    */
    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Absensi Mahasiswa',
        'kode_periode' => 'AM-' . fake()->unique()->numberBetween(1000, 9999),
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

    return [
        'user' => $user,
        'mahasiswa' => $mahasiswa,
        'mentor' => $mentor,
        'periode' => $periode,
        'penempatan' => $penempatan,
    ];
}

test('mahasiswa can access absensi page', function () {
    Carbon::setTestNow(
        Carbon::create(
            2026,
            8,
            24,
            8,
            0,
            0
        )
    );

    $data = createMahasiswaAbsensiUser();

    $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.absensi.index'))
        ->assertOk()
        ->assertViewIs('mahasiswa.absensi.index')
        ->assertViewHas('penempatan')
        ->assertViewHas('absensiHariIni');
});

test('mahasiswa can record absen masuk', function () {
    Carbon::setTestNow(
        Carbon::create(
            2026,
            8,
            24,
            8,
            0,
            0
        )
    );

    $data = createMahasiswaAbsensiUser();

    $response = $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.absensi.masuk'), [
            'keterangan' => null,
        ]);

    $response
        ->assertRedirect(route('mahasiswa.absensi.index'))
        ->assertSessionHas(
            'success',
            'Absen masuk berhasil dicatat.'
        );

    $absensi = Absensi::query()
        ->where(
            'penempatan_id',
            $data['penempatan']->id
        )
        ->first();

    expect($absensi)
        ->not
        ->toBeNull();

    expect(
        $absensi->tanggal->toDateString()
    )
        ->toBe('2026-08-24');

    expect($absensi->jam_masuk)
        ->toBe('08:00');

    expect($absensi->status_kehadiran)
        ->toBe('hadir');

    expect($absensi->menit_terlambat)
        ->toBeNull();

    expect($absensi->status_verifikasi)
        ->toBe('pending');
});

test('mahasiswa who arrives late is still marked hadir with lateness minutes', function () {
    Carbon::setTestNow(
        Carbon::create(
            2026,
            8,
            24,
            8,
            3,
            0
        )
    );

    $data = createMahasiswaAbsensiUser();

    $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.absensi.masuk'), [
            'keterangan' => null,
        ]);

    $absensi = Absensi::query()
        ->where(
            'penempatan_id',
            $data['penempatan']->id
        )
        ->first();

    expect($absensi)
        ->not
        ->toBeNull();

    expect($absensi->status_kehadiran)
        ->toBe('hadir');

    expect($absensi->menit_terlambat)
        ->toBe(3);
});

test('mahasiswa cannot record absen masuk twice on same day', function () {
    Carbon::setTestNow(
        Carbon::create(
            2026,
            8,
            24,
            8,
            0,
            0
        )
    );

    $data = createMahasiswaAbsensiUser();

    $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.absensi.masuk'), [
            'keterangan' => null,
        ])
        ->assertRedirect(route('mahasiswa.absensi.index'));

    $response = $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.absensi.masuk'), [
            'keterangan' => null,
        ]);

    $response
        ->assertRedirect()
        ->assertSessionHasErrors('absensi');

    expect(
        Absensi::where(
            'penempatan_id',
            $data['penempatan']->id
        )->count()
    )->toBe(1);
});

test('user without active placement cannot record absen masuk', function () {
    Carbon::setTestNow(
        Carbon::create(
            2026,
            8,
            24,
            8,
            0,
            0
        )
    );

    $user = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $response = $this
        ->actingAs($user)
        ->post(route('mahasiswa.absensi.masuk'), [
            'keterangan' => null,
        ]);

    $response
        ->assertRedirect()
        ->assertSessionHasErrors('absensi');

    expect(
        Absensi::count()
    )->toBe(0);
});

test('mentor cannot access mahasiswa absen masuk endpoint', function () {
    $mentor = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $this
        ->actingAs($mentor)
        ->post(route('mahasiswa.absensi.masuk'), [
            'keterangan' => null,
        ])
        ->assertForbidden();
});

test('mahasiswa cannot record attendance outside active period', function () {
    Carbon::setTestNow(
        Carbon::create(
            2026,
            8,
            24,
            8,
            0,
            0
        )
    );

    $data = createMahasiswaAbsensiUser();

    $data['periode']->update([
        'tanggal_mulai' => '2027-01-01',
        'tanggal_selesai' => '2027-02-01',
    ]);

    $response = $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.absensi.masuk'), [
            'keterangan' => null,
        ]);

    $response
        ->assertRedirect()
        ->assertSessionHasErrors('absensi');

    expect(
        Absensi::count()
    )->toBe(0);
});
