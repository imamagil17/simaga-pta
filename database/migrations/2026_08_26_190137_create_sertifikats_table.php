<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sertifikats', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Mahasiswa yang menerima sertifikat
            |--------------------------------------------------------------------------
            */
            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswas')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Penempatan yang menjadi dasar sertifikat
            |--------------------------------------------------------------------------
            */
            $table->foreignId('penempatan_id')
                ->constrained('penempatans')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Mentor yang mengajukan sertifikat
            |--------------------------------------------------------------------------
            */
            $table->foreignId('mentor_id')
                ->constrained('mentors')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Status pengajuan
            |--------------------------------------------------------------------------
            */
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            /*
            |--------------------------------------------------------------------------
            | Nomor sertifikat
            |--------------------------------------------------------------------------
            */
            $table->string('nomor_sertifikat', 100)
                ->nullable()
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | File sertifikat
            |--------------------------------------------------------------------------
            */
            $table->string('file_sertifikat', 500)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Admin yang memproses pengajuan
            |--------------------------------------------------------------------------
            */
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Alasan penolakan
            |--------------------------------------------------------------------------
            */
            $table->text('catatan')
                ->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Satu pengajuan untuk satu penempatan.
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

            $table->index([
                'mahasiswa_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sertifikats');
    }
};
