<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_harian_id')->constrained('laporan_harians')->cascadeOnDelete();
            $table->foreignId('proyek_id')->constrained('proyeks')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alfa']);
            $table->enum('durasi', ['Full', 'Setengah Hari'])->nullable();
            $table->string('keterangan')->nullable();
            $table->enum('status_validasi', ['Draft Pagi', 'Menunggu Persetujuan', 'Disetujui', 'Ditolak'])->default('Draft Pagi');
            $table->enum('status_pembayaran', ['Belum Dibayar', 'Dibayar'])->default('Belum Dibayar');
            $table->timestamps();
            $table->unique(['laporan_harian_id', 'pegawai_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};