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
        // KITA MEMBUAT TABEL: keuangan_proyeks
        Schema::create('keuangan_proyeks', function (Blueprint $table) {
            $table->id();

            // 1. Relasi ke Tabel Proyek (Sangat Penting)
            // Ini menghubungkan setiap transaksi ke satu proyek spesifik
            $table->foreignId('proyek_id')->constrained()->onDelete('cascade');

            // 2. Tipe Transaksi (Pemasukan vs Pengeluaran)
            // Pemasukan: Dana Termin dari Klien. Pengeluaran: Biaya di lapangan.
            $table->enum('tipe', ['Pemasukan', 'Pengeluaran']);

            // 3. Kategori Transaksi
            // Contoh: Termin Pembayaran, Upah Tukang, Material Besi, Sewa Molen, Operasional Bensin.
            $table->string('kategori');

            // 4. Nominal Uang
            // Menggunakan decimal agar perhitungan uang presisi (hingga ratusan miliar)
            $table->decimal('nominal', 15, 2);

            // 5. Tanggal Transaksi
            $table->date('tanggal');

            // 6. Keterangan Detail
            // Contoh: "Pembayaran termin 1 dari Bpk. Budi", "Beli semen 100 sak di Toko Ema"
            $table->text('keterangan');

            // 7. Bukti Transaksi (File Foto/Nota)
            // Menyimpan nama file bukti nota/kwitansi (nullable jika tidak ada)
            $table->string('bukti_file')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_proyeks');
    }
};
