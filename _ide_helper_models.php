<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $penempatan_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string|null $jam_masuk
 * @property string|null $jam_pulang
 * @property string $status_kehadiran
 * @property int|null $menit_terlambat
 * @property string $status_verifikasi
 * @property string|null $keterangan
 * @property string|null $alasan_penolakan
 * @property string|null $paraf_mahasiswa
 * @property \Illuminate\Support\Carbon|null $paraf_mahasiswa_at
 * @property string|null $paraf_mentor
 * @property \Illuminate\Support\Carbon|null $paraf_mentor_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Penempatan $penempatan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereAlasanPenolakan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereJamMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereJamPulang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereMenitTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereParafMahasiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereParafMahasiswaAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereParafMentor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereParafMentorAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi wherePenempatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereStatusKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereStatusVerifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereUpdatedAt($value)
 */
	class Absensi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $mahasiswa_id
 * @property int|null $penempatan_id
 * @property string $jenis_dokumen
 * @property string $nama_dokumen
 * @property string $nama_file
 * @property string $path_file
 * @property string|null $mime_type
 * @property int|null $ukuran_file
 * @property string $status
 * @property string|null $catatan
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @property-read \App\Models\Penempatan|null $penempatan
 * @property-read \App\Models\User|null $verifier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereJenisDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereMahasiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereNamaDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereNamaFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen wherePathFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen wherePenempatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereUkuranFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dokumen whereVerifiedBy($value)
 */
	class Dokumen extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $penempatan_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $judul_kegiatan
 * @property string $uraian_kegiatan
 * @property string|null $hasil_kegiatan
 * @property string|null $kendala
 * @property string|null $rencana_tindak_lanjut
 * @property string|null $bukti_kegiatan
 * @property string $status
 * @property string|null $catatan_mentor
 * @property \Illuminate\Support\Carbon|null $submitted_at
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Penempatan $penempatan
 * @property-read \App\Models\User|null $reviewer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereBuktiKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereCatatanMentor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereHasilKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereJudulKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereKendala($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook wherePenempatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereRencanaTindakLanjut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Logbook whereUraianKegiatan($value)
 */
	class Logbook extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $nim
 * @property string $perguruan_tinggi
 * @property string $program_studi
 * @property string|null $jenis_kelamin
 * @property string|null $agama
 * @property string|null $no_hp
 * @property string|null $alamat
 * @property string|null $foto
 * @property string $status
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Dokumen> $dokumen
 * @property-read int|null $dokumen_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Penempatan> $penempatans
 * @property-read int|null $penempatans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PengumpulanTugas> $pengumpulanTugas
 * @property-read int|null $pengumpulan_tugas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Penilaian> $penilaian
 * @property-read int|null $penilaian_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sertifikat> $sertifikat
 * @property-read int|null $sertifikat_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePerguruanTinggi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereProgramStudi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUserId($value)
 */
	class Mahasiswa extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string|null $nip
 * @property string|null $jabatan
 * @property string|null $bagian
 * @property string|null $jenis_kelamin
 * @property string|null $agama
 * @property string|null $no_hp
 * @property string|null $foto
 * @property string $status
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MentorPeriode> $mentorPeriodes
 * @property-read int|null $mentor_periodes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Penempatan> $penempatans
 * @property-read int|null $penempatans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Penilaian> $penilaian
 * @property-read int|null $penilaian_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sertifikat> $sertifikat
 * @property-read int|null $sertifikat_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereBagian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mentor whereUserId($value)
 */
	class Mentor extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $periode_magang_id
 * @property int $mentor_id
 * @property string $status
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mentor $mentor
 * @property-read \App\Models\PeriodeMagang $periodeMagang
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MentorPeriode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MentorPeriode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MentorPeriode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MentorPeriode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MentorPeriode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MentorPeriode whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MentorPeriode whereMentorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MentorPeriode wherePeriodeMagangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MentorPeriode whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MentorPeriode whereUpdatedAt($value)
 */
	class MentorPeriode extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $mahasiswa_id
 * @property int $periode_magang_id
 * @property int $mentor_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $tanggal_mulai
 * @property \Illuminate\Support\Carbon|null $tanggal_selesai
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Absensi> $absensis
 * @property-read int|null $absensis_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Dokumen> $dokumen
 * @property-read int|null $dokumen_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Logbook> $logbooks
 * @property-read int|null $logbooks_count
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @property-read \App\Models\Mentor $mentor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Penempatan> $penempatans
 * @property-read int|null $penempatans_count
 * @property-read \App\Models\Penilaian|null $penilaian
 * @property-read \App\Models\PeriodeMagang $periodeMagang
 * @property-read \App\Models\Sertifikat|null $sertifikat
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tugas> $tugas
 * @property-read int|null $tugas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan whereMahasiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan whereMentorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan wherePeriodeMagangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penempatan whereUpdatedAt($value)
 */
	class Penempatan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $tugas_id
 * @property int $mahasiswa_id
 * @property string|null $jawaban
 * @property string|null $file_jawaban
 * @property \Illuminate\Support\Carbon|null $dikumpulkan_at
 * @property string $status
 * @property numeric|null $nilai
 * @property string|null $catatan_mentor
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @property-read \App\Models\User|null $reviewer
 * @property-read \App\Models\Tugas $tugas
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereCatatanMentor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereDikumpulkanAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereFileJawaban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereJawaban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereMahasiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereNilai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereTugasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PengumpulanTugas whereUpdatedAt($value)
 */
	class PengumpulanTugas extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $mahasiswa_id
 * @property int $penempatan_id
 * @property int $mentor_id
 * @property numeric|null $nilai_kedisiplinan
 * @property numeric|null $nilai_kehadiran
 * @property numeric|null $nilai_kinerja
 * @property numeric|null $nilai_kompetensi
 * @property numeric|null $nilai_sikap
 * @property numeric|null $nilai_akhir
 * @property string|null $catatan
 * @property string $status
 * @property int|null $finalized_by
 * @property \Illuminate\Support\Carbon|null $finalized_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $finalizer
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @property-read \App\Models\Mentor $mentor
 * @property-read \App\Models\Penempatan $penempatan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereFinalizedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereFinalizedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereMahasiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereMentorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereNilaiAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereNilaiKedisiplinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereNilaiKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereNilaiKinerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereNilaiKompetensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereNilaiSikap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian wherePenempatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penilaian whereUpdatedAt($value)
 */
	class Penilaian extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama_periode
 * @property string $kode_periode
 * @property \Illuminate\Support\Carbon $tanggal_mulai
 * @property \Illuminate\Support\Carbon $tanggal_selesai
 * @property string $status
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MentorPeriode> $mentorPeriodes
 * @property-read int|null $mentor_periodes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Penempatan> $penempatans
 * @property-read int|null $penempatans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeMagang newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeMagang newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeMagang query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeMagang whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeMagang whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeMagang whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeMagang whereKodePeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeMagang whereNamaPeriode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeMagang whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeMagang whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeMagang whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeMagang whereUpdatedAt($value)
 */
	class PeriodeMagang extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $mahasiswa_id
 * @property int $penempatan_id
 * @property int $mentor_id
 * @property string $status
 * @property string|null $nomor_sertifikat
 * @property string|null $file_sertifikat
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @property-read \App\Models\Mentor $mentor
 * @property-read \App\Models\Penempatan $penempatan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat whereFileSertifikat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat whereMahasiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat whereMentorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat whereNomorSertifikat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat wherePenempatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sertifikat whereUpdatedAt($value)
 */
	class Sertifikat extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $penempatan_id
 * @property string $judul
 * @property string $deskripsi
 * @property \Illuminate\Support\Carbon $tanggal_mulai
 * @property \Illuminate\Support\Carbon $tanggal_deadline
 * @property string $status
 * @property string|null $file_tugas
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Penempatan $penempatan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PengumpulanTugas> $pengumpulan
 * @property-read int|null $pengumpulan_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereFileTugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas wherePenempatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereTanggalDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tugas whereUpdatedAt($value)
 */
	class Tugas extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string $status
 * @property int $must_change_password
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mahasiswa|null $mahasiswa
 * @property-read \App\Models\Mentor|null $mentor
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereMustChangePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

