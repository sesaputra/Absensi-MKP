<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kasbons', function (Blueprint $table) {
            $table->id();
            // Siapa yang pinjam uang?
            $table->foreignId('pegawai_id')->constrained()->onDelete('cascade');
            // Proyek mana yang terkait?
            $table->foreignId('proyek_id')->constrained()->onDelete('cascade');

            $table->date('tanggal');
            $table->decimal('nominal', 15, 2);
            $table->text('keterangan')->nullable();

            // Status untuk mengunci apakah kasbon ini sudah "terbayar" oleh sistem gajian
            $table->enum('status', ['Belum Lunas', 'Lunas'])->default('Belum Lunas');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kasbons');
    }
};
