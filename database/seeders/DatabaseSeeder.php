<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\Jabatan; // Jangan lupa panggil model Jabatan
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. MEMBUAT MASTER DATA JABATAN DULU
        $jabatanPengawas = Jabatan::create([
            'nama_jabatan' => 'Pengawas',
            'gaji_harian' => 150000, // Gaji harian simulasi
        ]);

        $jabatanTukang = Jabatan::create([
            'nama_jabatan' => 'Tukang',
            'gaji_harian' => 120000, // Gaji harian simulasi
        ]);

        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mkp.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        // 1. Membuat Akun Admin (Hanya untuk login)
        User::create([
            'name' => 'Administrator MKP',
            'email' => 'admin@mkp.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Membuat Akun Pengawas (Untuk login)
        $userPengawas = User::create([
            'name' => 'Ketut Muliawan',
            'email' => 'ketut@mkp.com',
            'password' => Hash::make('password123'),
            'role' => 'pengawas',
        ]);

        // 3. Membuat Profil Pegawai Ketut Muliawan (Pakai jabatan_id)
        Pegawai::create([
            'user_id' => $userPengawas->id,
            'nama' => 'Ketut Muliawan',
            'jabatan_id' => $jabatanPengawas->id, // Mengambil ID dari variabel jabatan di atas
            'no_telp' => '085739411214',
        ]);

        // 4. Menambahkan pegawai lain (Tukang) yang TIDAK butuh login (Pakai jabatan_id)
        Pegawai::create([
            'user_id' => null,
            'nama' => 'Jaenal',
            'jabatan_id' => $jabatanTukang->id, // Mengambil ID dari variabel jabatan di atas
            'no_telp' => '085739411215',
        ]);
    }
}
