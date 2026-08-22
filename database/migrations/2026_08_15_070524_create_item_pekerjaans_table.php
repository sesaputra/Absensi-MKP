<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained('proyeks')->cascadeOnDelete();

            $table->string('nama_pekerjaan'); // Contoh: "Pekerjaan Pondasi"
            $table->decimal('bobot', 5, 2); // Persentase dari total proyek (Maks 100.00)
            $table->decimal('progres_sekarang', 5, 2)->default(0); // Dimulai dari 0%

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_pekerjaans');
    }
};
