<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keuangan_proyeks', function (Blueprint $table) {
            $table->id();

            // Proyek pemilik transaksi
            $table->foreignId('proyek_id')
                ->constrained()
                ->onDelete('cascade');

            // Hanya terisi jika transaksi merupakan
            // pencairan kasbon tertentu.
            $table->foreignId('kasbon_id')
                ->nullable()
                ->constrained('kasbons')
                ->nullOnDelete();

            $table->enum('tipe', [
                'Pemasukan',
                'Pengeluaran',
            ]);

            $table->string('kategori');

            $table->decimal('nominal', 15, 2);

            $table->date('tanggal');

            $table->text('keterangan');

            $table->string('bukti_file')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan_proyeks');
    }
};