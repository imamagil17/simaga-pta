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
        Schema::create('mentor_periodes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('periode_magang_id')
                ->constrained('periode_magangs')
                ->cascadeOnDelete();

            $table->foreignId('mentor_id')
                ->constrained('mentors')
                ->cascadeOnDelete();

            $table->string('status')
                ->default('active');

            $table->text('keterangan')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'periode_magang_id',
                'mentor_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_periodes');
    }
};