<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyeks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_proyek');
            $table->string('nama_pemilik')->nullable();
            $table->string('kontak_pemilik')->nullable();
            $table->string('lokasi');
            $table->date('tanggal_mulai');
            $table->date('estimasi_selesai')->nullable();
            $table->bigInteger('anggaran')->default(0);
            $table->string('gambar')->nullable();

            $table->enum('status', ['Akan Dimulai', 'Berjalan', 'Ditunda', 'Selesai'])->default('Akan Dimulai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyeks');
    }
};
