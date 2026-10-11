<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Renamed from 2026_08_18_064720_create_kasbons_table (same table).
        // Databases migrated before the rename already have this table and
        // recorded the old filename — skip creation there so migrate keeps
        // moving instead of failing with "table already exists".
        if (Schema::hasTable('kasbons')) {
            return;
        }

        Schema::create('kasbons', function (Blueprint $table) {
            $table->id();

            // Pegawai yang menerima kasbon
            $table->foreignId('pegawai_id')
                ->constrained()
                ->onDelete('cascade');

            // Proyek yang memberikan kasbon
            $table->foreignId('proyek_id')
                ->constrained()
                ->onDelete('cascade');

            // Tanggal pencairan kasbon
            $table->date('tanggal');

            // Nominal awal kasbon
            $table->decimal('nominal', 15, 2);

            // Sisa kasbon yang belum dilunasi melalui payroll
            $table->decimal('sisa_nominal', 15, 2);

            // Keterangan kasbon
            $table->text('keterangan')->nullable();

            // Status pelunasan
            $table->enum('status', [
                'Belum Lunas',
                'Lunas',
            ])->default('Belum Lunas');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kasbons');
    }
};
