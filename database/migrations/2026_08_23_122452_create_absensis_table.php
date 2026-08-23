<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Relasi Penempatan
            |--------------------------------------------------------------------------
            */
            $table->foreignId('penempatan_id')
                ->constrained('penempatans')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Waktu Absensi
            |--------------------------------------------------------------------------
            */
            $table->date('tanggal');

            $table->time('jam_masuk')->nullable();

            $table->time('jam_pulang')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status Kehadiran
            |--------------------------------------------------------------------------
            |
            | Nilai:
            | hadir
            | izin
            | sakit
            | alpa
            |
            */
            $table->string('status_kehadiran')
                ->default('hadir');

            /*
            |--------------------------------------------------------------------------
            | Keterlambatan
            |--------------------------------------------------------------------------
            |
            | NULL  = tidak terlambat
            | 3     = terlambat 3 menit
            | 17    = terlambat 17 menit
            |
            */
            $table->unsignedInteger('menit_terlambat')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Verifikasi Mentor
            |--------------------------------------------------------------------------
            |
            | pending
            | approved
            | rejected
            |
            */
            $table->string('status_verifikasi')
                ->default('pending');

            $table->text('keterangan')
                ->nullable();

            $table->text('alasan_penolakan')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Paraf Digital Mahasiswa
            |--------------------------------------------------------------------------
            |
            | Menyimpan snapshot hasil tanda tangan digital dari canvas.
            |
            */
            $table->string('paraf_mahasiswa')
                ->nullable();

            $table->timestamp('paraf_mahasiswa_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Paraf Digital Mentor
            |--------------------------------------------------------------------------
            |
            | Menyimpan snapshot hasil tanda tangan digital mentor.
            |
            */
            $table->string('paraf_mentor')
                ->nullable();

            $table->timestamp('paraf_mentor_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Constraint
            |--------------------------------------------------------------------------
            |
            | Satu penempatan hanya memiliki satu absensi dalam satu hari.
            |
            */
            $table->unique([
                'penempatan_id',
                'tanggal',
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};