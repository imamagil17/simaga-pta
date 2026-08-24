<?php

use App\Models\Logbook;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMahasiswaLogbookData(array $periodeOverrides = []): array
{
    $user = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $user->id,
        'nim' => 'LGM-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'LGM-' . fake()->unique()->numberBetween(100000, 999999),
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'status' => 'active',
    ]);

    $periodeData = array_merge([
        'nama_periode' => 'Periode Logbook Mahasiswa',
        'kode_periode' => 'LGM-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2026-08-01',
        'tanggal_selesai' => '2026-11-30',
        'status' => 'active',
    ], $periodeOverrides);

    $periode = PeriodeMagang::create($periodeData);

    $penempatan = Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
        'status' => 'active',
    ]);

    return compact(
        'user',
        'mahasiswa',
        'mentorUser',
        'mentor',
        'periode',
        'penempatan'
    );
}

test('mahasiswa can access logbook page', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 09:00:00');

    $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.logbook.index'))
        ->assertOk()
        ->assertViewIs('mahasiswa.logbook.index')
        ->assertViewHas('penempatan')
        ->assertViewHas('logbooks')
        ->assertViewHas('hariKerja')
        ->assertViewHas('periodeBerjalan');

    Carbon::setTestNow();
});

test('mahasiswa can access create logbook page on weekday', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 09:00:00');

    $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.logbook.create'))
        ->assertOk()
        ->assertViewIs('mahasiswa.logbook.create')
        ->assertViewHas('penempatan')
        ->assertViewHas('tanggal');

    Carbon::setTestNow();
});

test('mahasiswa cannot create logbook on weekend', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-23 09:00:00');

    $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.logbook.create'))
        ->assertForbidden();

    Carbon::setTestNow();
});

test('mahasiswa cannot create logbook before period starts', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-07-31 09:00:00');

    $response = $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.logbook.create'));

    $response->assertForbidden();

    Carbon::setTestNow();
});

test('mahasiswa cannot create logbook after period ends', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-12-01 09:00:00');

    $response = $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.logbook.create'));

    $response->assertForbidden();

    Carbon::setTestNow();
});

test('mahasiswa can store logbook as draft', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 10:00:00');

    $response = $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.logbook.store'), [
            'judul_kegiatan' => 'Pengembangan Modul Absensi',
            'uraian_kegiatan' => 'Mengembangkan modul absensi mahasiswa pada SIMAGA PTA.',
            'hasil_kegiatan' => 'Modul absensi berhasil dikembangkan.',
            'kendala' => 'Tidak ada kendala berarti.',
            'rencana_tindak_lanjut' => 'Melanjutkan pengembangan modul berikutnya.',
        ]);

    $response
        ->assertRedirect(route('mahasiswa.logbook.index'))
        ->assertSessionHas('success');

    $logbook = Logbook::first();

    expect($logbook)->not->toBeNull();

    expect($logbook->penempatan_id)
        ->toBe($data['penempatan']->id);

    expect($logbook->tanggal->format('Y-m-d'))
        ->toBe('2026-08-24');

    expect($logbook->status)
        ->toBe('draft');

    Carbon::setTestNow();
});

test('mahasiswa cannot create second logbook on same day', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 10:00:00');

    Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Kegiatan Pertama',
        'uraian_kegiatan' => 'Kegiatan pertama mahasiswa.',
    ]);

    $response = $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.logbook.store'), [
            'judul_kegiatan' => 'Kegiatan Kedua',
            'uraian_kegiatan' => 'Kegiatan kedua mahasiswa.',
        ]);

    $response->assertRedirect(
        route('mahasiswa.logbook.index')
    );

    expect(Logbook::count())->toBe(1);

    Carbon::setTestNow();
});

test('mahasiswa can submit draft logbook', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 10:00:00');

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Pengembangan Sistem',
        'uraian_kegiatan' => 'Mengembangkan modul sistem.',
        'status' => 'draft',
    ]);

    $this
        ->actingAs($data['user'])
        ->post(route(
            'mahasiswa.logbook.submit',
            $logbook
        ))
        ->assertRedirect(route('mahasiswa.logbook.index'))
        ->assertSessionHas('success');

    $logbook->refresh();

    expect($logbook->status)
        ->toBe('submitted');

    expect($logbook->submitted_at)
        ->not->toBeNull();

    Carbon::setTestNow();
});

test('mahasiswa cannot submit another students logbook', function () {
    $data = createMahasiswaLogbookData();

    $other = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 10:00:00');

    $logbook = Logbook::create([
        'penempatan_id' => $other['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Kegiatan Mahasiswa Lain',
        'uraian_kegiatan' => 'Kegiatan mahasiswa lain.',
        'status' => 'draft',
    ]);

    $this
        ->actingAs($data['user'])
        ->post(route(
            'mahasiswa.logbook.submit',
            $logbook
        ))
        ->assertForbidden();

    Carbon::setTestNow();
});

test('mentor cannot access mahasiswa logbook page', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 10:00:00');

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mahasiswa.logbook.index'))
        ->assertForbidden();

    Carbon::setTestNow();
});

test('mahasiswa can access edit draft logbook', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 10:00:00');

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Judul Lama',
        'uraian_kegiatan' => 'Uraian kegiatan lama.',
        'status' => 'draft',
    ]);

    $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.logbook.edit', $logbook))
        ->assertOk()
        ->assertViewIs('mahasiswa.logbook.edit')
        ->assertViewHas('logbook');

    Carbon::setTestNow();
});

test('mahasiswa can update draft logbook', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 10:00:00');

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Judul Lama',
        'uraian_kegiatan' => 'Uraian kegiatan lama.',
        'status' => 'draft',
    ]);

    $this
        ->actingAs($data['user'])
        ->put(route('mahasiswa.logbook.update', $logbook), [
            'judul_kegiatan' => 'Judul Baru',
            'uraian_kegiatan' => 'Uraian kegiatan yang sudah diperbarui.',
            'hasil_kegiatan' => 'Hasil kegiatan terbaru.',
            'kendala' => 'Tidak ada kendala.',
            'rencana_tindak_lanjut' => 'Melanjutkan kegiatan.',
        ])
        ->assertRedirect(route('mahasiswa.logbook.index'))
        ->assertSessionHas('success');

    $logbook->refresh();

    expect($logbook->judul_kegiatan)
        ->toBe('Judul Baru');

    expect($logbook->uraian_kegiatan)
        ->toBe('Uraian kegiatan yang sudah diperbarui.');

    expect($logbook->tanggal->format('Y-m-d'))
        ->toBe('2026-08-24');

    expect($logbook->status)
        ->toBe('draft');

    Carbon::setTestNow();
});

test('mahasiswa can edit revision logbook', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 10:00:00');

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Judul Revisi',
        'uraian_kegiatan' => 'Uraian yang perlu diperbaiki.',
        'status' => 'revision',
        'catatan_mentor' => 'Mohon uraian diperjelas.',
    ]);

    $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.logbook.edit', $logbook))
        ->assertOk()
        ->assertViewIs('mahasiswa.logbook.edit');

    $this
        ->actingAs($data['user'])
        ->put(route('mahasiswa.logbook.update', $logbook), [
            'judul_kegiatan' => 'Judul Setelah Revisi',
            'uraian_kegiatan' => 'Uraian yang sudah diperbaiki dengan lebih jelas.',
        ])
        ->assertRedirect(route('mahasiswa.logbook.index'));

    $logbook->refresh();

    expect($logbook->judul_kegiatan)
        ->toBe('Judul Setelah Revisi');

    expect($logbook->status)
        ->toBe('revision');

    Carbon::setTestNow();
});

test('submitted logbook cannot be edited', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 10:00:00');

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Sudah Submit',
        'uraian_kegiatan' => 'Logbook sudah dikirim ke mentor.',
        'status' => 'submitted',
    ]);

    $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.logbook.edit', $logbook))
        ->assertRedirect(route('mahasiswa.logbook.index'));

    Carbon::setTestNow();
});

test('approved logbook cannot be edited', function () {
    $data = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 10:00:00');

    $logbook = Logbook::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Sudah Disetujui',
        'uraian_kegiatan' => 'Logbook sudah disetujui mentor.',
        'status' => 'approved',
    ]);

    $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.logbook.edit', $logbook))
        ->assertRedirect(route('mahasiswa.logbook.index'));

    Carbon::setTestNow();
});

test('mahasiswa cannot edit another students logbook', function () {
    $data = createMahasiswaLogbookData();

    $other = createMahasiswaLogbookData();

    Carbon::setTestNow('2026-08-24 10:00:00');

    $logbook = Logbook::create([
        'penempatan_id' => $other['penempatan']->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Logbook Mahasiswa Lain',
        'uraian_kegiatan' => 'Ini bukan milik mahasiswa pertama.',
        'status' => 'draft',
    ]);

    $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.logbook.edit', $logbook))
        ->assertForbidden();

    Carbon::setTestNow();
});