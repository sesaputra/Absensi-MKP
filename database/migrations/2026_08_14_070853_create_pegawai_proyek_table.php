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
        Schema::create('pegawai_proyek', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel proyeks
            $table->foreignId('proyek_id')->constrained('proyeks')->cascadeOnDelete();

            // Relasi ke tabel pegawais
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();

            $table->timestamps();

            // Mencegah duplikasi: 1 pegawai tidak bisa dimasukkan 2x di proyek yang sama
            $table->unique(['proyek_id', 'pegawai_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_proyek');
    }
};
