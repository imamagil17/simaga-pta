<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswas')
                ->cascadeOnDelete();

            $table->foreignId('penempatan_id')
                ->constrained('penempatans')
                ->cascadeOnDelete();

            $table->foreignId('mentor_id')
                ->constrained('mentors')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Nilai tiap aspek
            |--------------------------------------------------------------------------
            */
            $table->decimal(
                'nilai_kedisiplinan',
                5,
                2
            )->nullable();

            $table->decimal(
                'nilai_kehadiran',
                5,
                2
            )->nullable();

            $table->decimal(
                'nilai_kinerja',
                5,
                2
            )->nullable();

            $table->decimal(
                'nilai_kompetensi',
                5,
                2
            )->nullable();

            $table->decimal(
                'nilai_sikap',
                5,
                2
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | Nilai akhir otomatis
            |--------------------------------------------------------------------------
            */
            $table->decimal(
                'nilai_akhir',
                5,
                2
            )->nullable();

            /*
            |--------------------------------------------------------------------------
            | Catatan mentor
            |--------------------------------------------------------------------------
            */
            $table->text('catatan')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'draft',
                'final',
            ])->default('draft');

            /*
            |--------------------------------------------------------------------------
            | Finalisasi
            |--------------------------------------------------------------------------
            */
            $table->foreignId('finalized_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('finalized_at')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Satu penilaian untuk satu mahasiswa pada satu penempatan.
            |--------------------------------------------------------------------------
            */
            $table->unique([
                'mahasiswa_id',
                'penempatan_id',
            ]);

            $table->index([
                'mentor_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
