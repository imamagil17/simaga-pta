<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penempatan_id')
                ->constrained('penempatans')
                ->cascadeOnDelete();

            $table->date('tanggal');

            $table->string('judul_kegiatan', 150);

            $table->text('uraian_kegiatan');

            $table->text('hasil_kegiatan')->nullable();

            $table->text('kendala')->nullable();

            $table->text('rencana_tindak_lanjut')->nullable();

            $table->string('bukti_kegiatan')->nullable();

            $table->enum('status', [
                'draft',
                'submitted',
                'approved',
                'revision',
            ])->default('draft');

            $table->text('catatan_mentor')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->timestamp('reviewed_at')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Satu penempatan hanya boleh memiliki satu logbook per tanggal.
            |--------------------------------------------------------------------------
            */
            $table->unique([
                'penempatan_id',
                'tanggal',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
