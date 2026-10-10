<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\Jabatan; // Jangan lupa panggil model Jabatan
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Idempotent: safe to run on every deploy. Every record uses
     * firstOrCreate / exists-checks, so re-running never duplicates
     * rows and never touches (resets) existing accounts.
     */
    public function run(): void
    {
        // 0. MEMBUAT MASTER DATA JABATAN DULU (hanya jika belum ada)
        $jabatanPengawas = Jabatan::firstOrCreate(
            ['nama_jabatan' => 'Pengawas'],
            ['gaji_harian' => 150000], // Gaji harian simulasi
        );

        $jabatanTukang = Jabatan::firstOrCreate(
            ['nama_jabatan' => 'Tukang'],
            ['gaji_harian' => 120000], // Gaji harian simulasi
        );

        // Akun default dibuat hanya jika email belum terdaftar.
        // Akun yang sudah ada TIDAK diubah (password/role aman).
        User::firstOrCreate(
            ['email' => 'superadmin@mkp.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
            ]
        );

        // 1. Membuat Akun Admin (Hanya untuk login)
        User::firstOrCreate(
            ['email' => 'admin@mkp.com'],
            [
                'name' => 'Administrator MKP',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 2. Membuat Akun Pengawas (Untuk login)
        $userPengawas = User::firstOrCreate(
            ['email' => 'ketut@mkp.com'],
            [
                'name' => 'Ketut Muliawan',
                'password' => Hash::make('password123'),
                'role' => 'pengawas',
            ]
        );

        // 3. Membuat Profil Pegawai Ketut Muliawan (Pakai jabatan_id)
        Pegawai::firstOrCreate(
            ['user_id' => $userPengawas->id],
            [
                'nama' => 'Ketut Muliawan',
                'jabatan_id' => $jabatanPengawas->id, // Mengambil ID dari variabel jabatan di atas
                'no_telp' => '085739411214',
            ]
        );

        // 4. Menambahkan pegawai lain (Tukang) yang TIDAK butuh login (Pakai jabatan_id)
        if (! Pegawai::where('nama', 'Jaenal')->whereNull('user_id')->exists()) {
            Pegawai::create([
                'user_id' => null,
                'nama' => 'Jaenal',
                'jabatan_id' => $jabatanTukang->id, // Mengambil ID dari variabel jabatan di atas
                'no_telp' => '085739411215',
            ]);
        }
    }
}
