<?php

use App\Models\Mentor;
use App\Models\MentorPeriode;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/

function createAdministrator(): User
{
    return User::factory()->create([
        'role' => 'administrator',
        'status' => 'active',
        'must_change_password' => false,
    ]);
}

function createMentorWithProfile(
    string $userStatus = 'active',
    string $mentorStatus = 'active'
): Mentor {
    $user = User::factory()->create([
        'role' => 'mentor',
        'status' => $userStatus,
        'must_change_password' => false,
    ]);

    return Mentor::create([
        'user_id' => $user->id,
        'nip' => '198501012010011001',
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'no_hp' => '081234567890',
        'status' => $mentorStatus,
    ]);
}

function createPeriode(): PeriodeMagang
{
    return PeriodeMagang::create([
        'nama_periode' => 'Magang Semester Ganjil 2026',
        'kode_periode' => 'MAG-2026-GANJIL-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2026-08-01',
        'tanggal_selesai' => '2026-11-30',
        'status' => 'active',
        'keterangan' => 'Periode testing.',
    ]);
}

/*
|--------------------------------------------------------------------------
| Access
|--------------------------------------------------------------------------
*/

test('administrator can access mentor assignment page', function () {
    $admin = createAdministrator();
    $periode = createPeriode();

    $response = $this
        ->actingAs($admin)
        ->get(route('admin.periode-magangs.mentors.index', $periode));

    $response
        ->assertOk()
        ->assertViewIs('admin.periode-magangs.mentors.index')
        ->assertViewHas('periodeMagang', $periode);
});

test('mentor cannot access mentor assignment page', function () {
    $mentor = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $periode = createPeriode();

    $this
        ->actingAs($mentor)
        ->get(route('admin.periode-magangs.mentors.index', $periode))
        ->assertForbidden();
});

test('mahasiswa cannot access mentor assignment page', function () {
    $mahasiswa = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $periode = createPeriode();

    $this
        ->actingAs($mahasiswa)
        ->get(route('admin.periode-magangs.mentors.index', $periode))
        ->assertForbidden();
});

/*
|--------------------------------------------------------------------------
| Store
|--------------------------------------------------------------------------
*/

test('administrator can assign active mentor to active period', function () {
    $admin = createAdministrator();
    $periode = createPeriode();
    $mentor = createMentorWithProfile();

    $response = $this
        ->actingAs($admin)
        ->post(route('admin.periode-magangs.mentors.store', $periode), [
            'mentor_id' => $mentor->id,
            'status' => 'active',
            'keterangan' => 'Mentor pembimbing periode.',
        ]);

    $response
        ->assertRedirect(
            route('admin.periode-magangs.mentors.index', $periode)
        )
        ->assertSessionHas(
            'success',
            'Mentor berhasil ditambahkan ke periode magang.'
        );

    $this->assertDatabaseHas('mentor_periodes', [
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
        'status' => 'active',
        'keterangan' => 'Mentor pembimbing periode.',
    ]);
});

test('inactive mentor profile cannot be assigned', function () {
    $admin = createAdministrator();
    $periode = createPeriode();
    $mentor = createMentorWithProfile(
        userStatus: 'active',
        mentorStatus: 'inactive'
    );

    $response = $this
        ->actingAs($admin)
        ->post(route('admin.periode-magangs.mentors.store', $periode), [
            'mentor_id' => $mentor->id,
            'status' => 'active',
        ]);

    $response
        ->assertSessionHasErrors('mentor_id');

    $this->assertDatabaseMissing('mentor_periodes', [
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
    ]);
});

test('inactive mentor account cannot be assigned', function () {
    $admin = createAdministrator();
    $periode = createPeriode();
    $mentor = createMentorWithProfile(
        userStatus: 'inactive',
        mentorStatus: 'active'
    );

    $response = $this
        ->actingAs($admin)
        ->post(route('admin.periode-magangs.mentors.store', $periode), [
            'mentor_id' => $mentor->id,
            'status' => 'active',
        ]);

    $response
        ->assertSessionHasErrors('mentor_id');

    $this->assertDatabaseMissing('mentor_periodes', [
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
    ]);
});

test('same mentor cannot be assigned twice to the same period', function () {
    $admin = createAdministrator();
    $periode = createPeriode();
    $mentor = createMentorWithProfile();

    MentorPeriode::create([
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
        'status' => 'active',
    ]);

    $response = $this
        ->actingAs($admin)
        ->post(route('admin.periode-magangs.mentors.store', $periode), [
            'mentor_id' => $mentor->id,
            'status' => 'active',
        ]);

    $response
        ->assertSessionHasErrors('mentor_id');

    expect(
        MentorPeriode::where('periode_magang_id', $periode->id)
            ->where('mentor_id', $mentor->id)
            ->count()
    )->toBe(1);
});

test('mentor can be assigned to different periods', function () {
    $admin = createAdministrator();
    $periodeA = createPeriode();
    $periodeB = PeriodeMagang::create([
        'nama_periode' => 'Magang Semester Genap 2027',
        'kode_periode' => 'MAG-2027-GENAP-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2027-01-01',
        'tanggal_selesai' => '2027-04-30',
        'status' => 'active',
        'keterangan' => 'Periode testing kedua.',
    ]);

    $mentor = createMentorWithProfile();

    $this
        ->actingAs($admin)
        ->post(route('admin.periode-magangs.mentors.store', $periodeA), [
            'mentor_id' => $mentor->id,
            'status' => 'active',
        ])
        ->assertRedirect();

    $this
        ->actingAs($admin)
        ->post(route('admin.periode-magangs.mentors.store', $periodeB), [
            'mentor_id' => $mentor->id,
            'status' => 'active',
        ])
        ->assertRedirect();

    expect(
        MentorPeriode::where('mentor_id', $mentor->id)->count()
    )->toBe(2);
});

/*
|--------------------------------------------------------------------------
| Update Status
|--------------------------------------------------------------------------
*/

test('administrator can deactivate mentor assignment', function () {
    $admin = createAdministrator();
    $periode = createPeriode();
    $mentor = createMentorWithProfile();

    $mentorPeriode = MentorPeriode::create([
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
        'status' => 'active',
    ]);

    $this
        ->actingAs($admin)
        ->put(route('admin.mentor-periodes.update', $mentorPeriode), [
            'status' => 'inactive',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('mentor_periodes', [
        'id' => $mentorPeriode->id,
        'status' => 'inactive',
    ]);
});

test('administrator can reactivate mentor assignment', function () {
    $admin = createAdministrator();
    $periode = createPeriode();
    $mentor = createMentorWithProfile();

    $mentorPeriode = MentorPeriode::create([
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
        'status' => 'inactive',
    ]);

    $this
        ->actingAs($admin)
        ->put(route('admin.mentor-periodes.update', $mentorPeriode), [
            'status' => 'active',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('mentor_periodes', [
        'id' => $mentorPeriode->id,
        'status' => 'active',
    ]);
});

test('non administrator cannot update mentor assignment', function () {
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $periode = createPeriode();
    $mentor = createMentorWithProfile();

    $mentorPeriode = MentorPeriode::create([
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
        'status' => 'active',
    ]);

    $this
        ->actingAs($mentorUser)
        ->put(route('admin.mentor-periodes.update', $mentorPeriode), [
            'status' => 'inactive',
        ])
        ->assertForbidden();

    $this->assertDatabaseHas('mentor_periodes', [
        'id' => $mentorPeriode->id,
        'status' => 'active',
    ]);
});

/*
|--------------------------------------------------------------------------
| Relationships
|--------------------------------------------------------------------------
*/

test('periode has many mentor assignments', function () {
    $periode = createPeriode();
    $mentor = createMentorWithProfile();

    $mentorPeriode = MentorPeriode::create([
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
        'status' => 'active',
    ]);

    expect($periode->mentorPeriodes)
        ->toHaveCount(1)
        ->and($periode->mentorPeriodes->first()->id)
        ->toBe($mentorPeriode->id);
});

test('mentor has many period assignments', function () {
    $periode = createPeriode();
    $mentor = createMentorWithProfile();

    $mentorPeriode = MentorPeriode::create([
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
        'status' => 'active',
    ]);

    expect($mentor->mentorPeriodes)
        ->toHaveCount(1)
        ->and($mentor->mentorPeriodes->first()->id)
        ->toBe($mentorPeriode->id);
});

test('mentor periode belongs to period and mentor', function () {
    $periode = createPeriode();
    $mentor = createMentorWithProfile();

    $mentorPeriode = MentorPeriode::create([
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
        'status' => 'active',
    ]);

    expect($mentorPeriode->periodeMagang->id)->toBe($periode->id);
    expect($mentorPeriode->mentor->id)->toBe($mentor->id);
});