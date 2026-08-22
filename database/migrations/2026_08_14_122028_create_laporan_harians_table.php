<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained('proyeks')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('foto_pagi')->nullable();
            $table->string('foto_sore')->nullable();
            $table->text('kegiatan')->nullable();
            // KOLOM BARU: REKAM JEJAK PROGRES FISIK (JSON)
            $table->json('progres_snapshot')->nullable();
            $table->enum('status_validasi', ['Draft Pagi', 'Menunggu Persetujuan', 'Disetujui', 'Ditolak'])->default('Draft Pagi');
            // KOLOM LOKASI & WAKTU REAL-TIME
            $table->string('latitude')->nullable();    // Koordinat Garis Lintang
            $table->string('longitude')->nullable();   // Koordinat Garis Bujur
            $table->timestamp('waktu_absen')->nullable(); // Waktu pasti saat Mandor menekan "Kirim"

            $table->timestamps();
            $table->unique(['proyek_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_harians');
    }
};
