<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kasbon;
use App\Models\Proyek;
use App\Models\KeuanganProyek;
use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KeuanganProyekController extends Controller
{
    public function index(Proyek $proyek)
    {
        // 1. Tarik semua transaksi
        $transaksi = KeuanganProyek::where('proyek_id', $proyek->id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. LOGIKA PERHITUNGAN BARU (Berdasarkan Arus Kas Nyata)
        $totalAnggaran = $proyek->anggaran; // RAB Total

        $totalPengeluaran = KeuanganProyek::where('proyek_id', $proyek->id)
            ->where('tipe', 'Pengeluaran')
            ->sum('nominal');

        $totalPemasukan = KeuanganProyek::where('proyek_id', $proyek->id)
            ->where('tipe', 'Pemasukan')
            ->sum('nominal');

        // Uang cash nyata yang ada di tangan (Termin Cair - Pengeluaran)
        $kasTersedia = $totalPemasukan - $totalPengeluaran;

        // Persentase seberapa banyak uang termin yang sudah dibakar/dipakai
        $persentaseKasTerpakai = 0;
        if ($totalPemasukan > 0) {
            $persentaseKasTerpakai = ($totalPengeluaran / $totalPemasukan) * 100;
        }

        // ==========================================
        // LOGIKA HYBRID THRESHOLD (Sistem Gabungan)
        // ==========================================
        $batasAmanNominal = 5000000; // Rp 5.000.000 (Bisa disesuaikan nanti)

        $progressColor = 'bg-green-500';
        $statusText = number_format($persentaseKasTerpakai, 1) . '% Terpakai';
        $statusColor = 'text-green-500';

        if ($kasTersedia < 0) {
            // Skenario 1: Saldo Minus (Nombok)
            $progressColor = 'bg-red-600';
            $statusText = 'Darurat: Proyek Nombok!';
            $statusColor = 'text-red-600';
            $persentaseKasTerpakai = 100; // Penuhkan bar jadi merah full

        } elseif ($kasTersedia > 0 && $kasTersedia <= $batasAmanNominal) {
            // Skenario 2: Kas di bawah batas aman (Walau persentase mungkin baru 50%)
            $progressColor = 'bg-red-500';
            $statusText = 'Kritis: Kas < Rp 5 Jt';
            $statusColor = 'text-red-500';
        } elseif ($persentaseKasTerpakai >= 90) {
            // Skenario 3: Uang sudah terpakai lebih dari 90%
            $progressColor = 'bg-red-500';
            $statusText = 'Kritis: Dana > 90%';
            $statusColor = 'text-red-500';
        } elseif ($persentaseKasTerpakai >= 75) {
            // Skenario 4: Peringatan kuning (Hati-hati)
            $progressColor = 'bg-amber-500';
            $statusText = 'Awas: Dana > 75%';
            $statusColor = 'text-amber-500';
        }

        // UBAH NAMA VARIABEL DI COMPACT
        return view('admin.laporan-keuangan', compact(
            'proyek',
            'transaksi',
            'totalAnggaran',
            'totalPengeluaran',
            'totalPemasukan',
            'kasTersedia',
            'persentaseKasTerpakai',
            'progressColor',
            'statusText',   // Variabel Baru (Label Cerdas)
            'statusColor'   // Variabel Baru (Warna Cerdas)
        ));
    }

    public function store(Request $request, Proyek $proyek)
    {
        // 1. Validasi inputan dari form
        $request->validate([
            'tipe' => 'required|in:Pemasukan,Pengeluaran',
            'kategori' => 'required|string',
            'nominal' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
            'bukti_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048', // Maksimal file 2MB
        ]);

        // 2. Siapkan array data yang akan disimpan
        $data = [
            'proyek_id' => $proyek->id,
            'tipe' => $request->tipe,
            'kategori' => $request->kategori,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ];

        // 3. Proses jika ada file/foto nota yang diupload
        if ($request->hasFile('bukti_file')) {
            // File akan disimpan di storage/app/public/bukti_keuangan
            $path = $request->file('bukti_file')->store('bukti_keuangan', 'public');
            $data['bukti_file'] = $path;
        }

        // 4. Simpan ke database
        KeuanganProyek::create($data);

        // 5. Kembali ke halaman sebelumnya dengan pesan sukses
        $pesan = $request->tipe == 'Pemasukan' ? 'Termin Klien berhasil dicatat!' : 'Biaya Lapangan berhasil dicatat!';
        return back()->with('success', $pesan);
    }

    // 1. Fungsi untuk Menghitung Gaji (Mengembalikan JSON untuk Modal)
    public function previewGaji(Request $request, Proyek $proyek)
    {
        $request->validate([
            'pegawai_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $pegawai = Pegawai::with('jabatan')->findOrFail($request->pegawai_id);
        $gajiHarian = $pegawai->jabatan->gaji_harian;

        // Hitung Kehadiran (Hadir & Disetujui)
        $kehadiran = Absensi::where('pegawai_id', $pegawai->id)
            ->where('proyek_id', $proyek->id)
            ->whereBetween('tanggal', [$request->start_date, $request->end_date])
            ->where('status', 'Hadir')
            ->where('status_validasi', 'Disetujui')
            ->where('status_pembayaran', 'Belum Dibayar')
            ->get();

        $hariFull = $kehadiran->where('durasi', 'Full')->count();
        $hariSetengah = $kehadiran->where('durasi', 'Setengah Hari')->count();

        // Kalkulasi Upah
        $totalUpahKotor = ($hariFull * $gajiHarian) + ($hariSetengah * ($gajiHarian / 2));

        // Cek Kasbon Belum Lunas
        $totalPotonganKasbon = Kasbon::where('pegawai_id', $pegawai->id)
            ->where('proyek_id', $proyek->id)
            ->where('status', 'Belum Lunas')
            ->sum('nominal');

        $estimasiGajiBersih = $totalUpahKotor - $totalPotonganKasbon;

        // Kembalikan data dalam bentuk JSON agar dibaca oleh JavaScript
        return response()->json([
            'status' => 'success',
            'data' => [
                'nama_pegawai' => $pegawai->nama,
                'gaji_harian' => $gajiHarian,
                'hari_full' => $hariFull,
                'hari_setengah' => $hariSetengah,
                'total_upah_kotor' => $totalUpahKotor,
                'total_potongan_kasbon' => $totalPotonganKasbon,
                'estimasi_gaji_bersih' => $estimasiGajiBersih,
                'id_absensi' => $kehadiran->pluck('id')->implode(','),
            ]
        ]);
    }

    // 2. Fungsi untuk Eksekusi Pembayaran & Konversi Kasbon
    public function bayarGaji(Request $request, Proyek $proyek)
    {
        $request->validate([
            'pegawai_id' => 'required',
            'nominal_dibayar' => 'required|numeric|min:0',
            'estimasi_gaji_sistem' => 'required|numeric',
            'id_absensi' => 'nullable|string',
        ]);

        // Gunakan DB Transaction agar uang aman jika tiba-tiba mati lampu/error
        DB::transaction(function () use ($request, $proyek) {
            if ($request->filled('id_absensi')) {
                $idAbsensis = explode(',', $request->id_absensi);
                Absensi::whereIn('id', $idAbsensis)->update(['status_pembayaran' => 'Dibayar']);
            }
            // 1. LUNASKAN SEMUA KASBON LAMA (Karena sudah dihitung di kertas buram)
            Kasbon::where('pegawai_id', $request->pegawai_id)
                ->where('proyek_id', $proyek->id)
                ->where('status', 'Belum Lunas')
                ->update(['status' => 'Lunas']);

            // 2. CEK LEBIH BAYAR (Auto-Konversi Kasbon)
            $selisih = $request->nominal_dibayar - $request->estimasi_gaji_sistem;

            if ($selisih > 0) {
                // Kakak Ipar ngasi uang lebih -> Jadikan Kasbon Baru!
                Kasbon::create([
                    'proyek_id' => $proyek->id,
                    'pegawai_id' => $request->pegawai_id,
                    'tanggal' => date('Y-m-d'),
                    'nominal' => $selisih,
                    'keterangan' => 'Sisa hutang / Kelebihan bayar dari gajian tanggal ' . date('d/m/Y'),
                    'status' => 'Belum Lunas',
                ]);
            }

            // 3. CATAT KE BUKU KAS (Hanya jika ada uang yang benar-benar keluar)
            if ($request->nominal_dibayar > 0) {
                KeuanganProyek::create([
                    'proyek_id' => $proyek->id,
                    'tipe' => 'Pengeluaran',
                    'kategori' => 'Upah Tukang',
                    'nominal' => $request->nominal_dibayar,
                    'tanggal' => date('Y-m-d'),
                    'keterangan' => 'Pembayaran Gaji a.n ' . Pegawai::find($request->pegawai_id)->nama . ' (Periode: ' . $request->start_date . ' s/d ' . $request->end_date . ')',
                ]);
            }
        });

        return redirect()->route('proyek.keuangan', $proyek->id)->with('success', 'Gaji berhasil dibayarkan dan Buku Kas telah diperbarui!');
    }

    // ========================================================
    // FITUR REKAP GAJI MASSAL (BULK PAYROLL)
    // ========================================================

    public function payrollIndex(Request $request, Proyek $proyek)
    {
        // 1. Tentukan Range Tanggal (Default: 7 hari ke belakang)
        $startDate = $request->input('start_date', now()->subDays(6)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // 2. Cari semua absensi yang BELUM DIBAYAR pada rentang tanggal tersebut
        $absensis = Absensi::with('pegawai.jabatan')
            ->where('proyek_id', $proyek->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('status', 'Hadir')
            ->where('status_validasi', 'Disetujui')
            ->where('status_pembayaran', 'Belum Dibayar') // <--- GEMBOK KITA BEKERJA DI SINI!
            ->get();

        // 3. Kelompokkan data absen berdasarkan Pekerja (Grouping)
        $rekapGaji = [];

        foreach ($absensis as $absen) {
            $pegawaiId = $absen->pegawai_id;

            // Jika pekerja belum masuk ke array rekap, buatkan wadahnya
            if (!isset($rekapGaji[$pegawaiId])) {
                $rekapGaji[$pegawaiId] = [
                    'pegawai' => $absen->pegawai,
                    'hari_full' => 0,
                    'hari_setengah' => 0,
                    'id_absensi' => [], // Simpan ID absen untuk di-update nanti
                ];
            }

            // Tambahkan hitungan hari & Simpan ID Absensi
            if ($absen->durasi == 'Full') {
                $rekapGaji[$pegawaiId]['hari_full'] += 1;
            } elseif ($absen->durasi == 'Setengah Hari') {
                $rekapGaji[$pegawaiId]['hari_setengah'] += 1;
            }
            $rekapGaji[$pegawaiId]['id_absensi'][] = $absen->id;
        }

        // 4. Hitung Uang & Potongan Kasbon untuk setiap pekerja
        foreach ($rekapGaji as $key => $data) {
            $gajiHarian = $data['pegawai']->jabatan->gaji_harian ?? 0;

            $upahKotor = ($data['hari_full'] * $gajiHarian) + ($data['hari_setengah'] * ($gajiHarian / 2));

            // Cari potongan kasbon
            $potonganKasbon = Kasbon::where('pegawai_id', $data['pegawai']->id)
                ->where('proyek_id', $proyek->id)
                ->where('status', 'Belum Lunas')
                ->sum('nominal');

            $rekapGaji[$key]['upah_kotor'] = $upahKotor;
            $rekapGaji[$key]['potongan_kasbon'] = $potonganKasbon;
            $rekapGaji[$key]['upah_bersih'] = $upahKotor - $potonganKasbon;
        }

        return view('admin.payroll', compact('proyek', 'rekapGaji', 'startDate', 'endDate'));
    }


    // Fungsi eksekusi pembayaran akan kita buat setelah UI View selesai
    public function payrollStore(Request $request, Proyek $proyek)
    {
        // 1. VALIDASI KETAT (Pertahanan Lapis Kedua)
        $request->validate([
            'bukti_file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', // Wajib, format gambar/pdf, max 2MB
            'pembayaran' => 'required|array',
            'periode_start' => 'required|date',
            'periode_end' => 'required|date',
        ], [
            // Pesan error custom (opsional, agar lebih ramah dibaca)
            'bukti_file.required' => 'Bukti serah terima uang wajib diunggah!',
            'bukti_file.mimes' => 'Format bukti harus berupa foto (JPG/PNG) atau PDF.',
            'bukti_file.max' => 'Ukuran file bukti maksimal adalah 2 Megabyte.',
        ]);

        // Gunakan DB Transaction agar data aman. Jika di tengah jalan error, semua batal.
        DB::transaction(function () use ($request, $proyek) {

            // Tangkap data array dari tabel HTML
            $pembayaran = $request->input('pembayaran', []);
            $periodeStart = $request->input('periode_start');
            $periodeEnd = $request->input('periode_end');

            $totalKasKeluar = 0;

            // Looping (Ulangi) untuk setiap pekerja yang ada di tabel
            foreach ($pembayaran as $pegawaiId => $data) {
                $nominalDibayar = $data['nominal'];
                $estimasiSistem = $data['estimasi_sistem'];

                // Pecah ID Absensi dari bentuk string "1,2,3" menjadi array [1, 2, 3]
                $idAbsensis = explode(',', $data['id_absensi']);

                // 1. GEMBOK ABSENSI: Ubah status menjadi 'Dibayar'
                if (!empty($data['id_absensi'])) {
                    Absensi::whereIn('id', $idAbsensis)->update(['status_pembayaran' => 'Dibayar']);
                }

                // 2. LUNASKAN KASBON LAMA (Karena potongannya sudah dihitung di estimasi sistem)
                Kasbon::where('pegawai_id', $pegawaiId)
                    ->where('proyek_id', $proyek->id)
                    ->where('status', 'Belum Lunas')
                    ->update(['status' => 'Lunas']);

                // 3. AUTO-KONVERSI KASBON BARU (Jika Mandor/Admin ngasi uang lebih dari hak sistem)
                $selisih = $nominalDibayar - $estimasiSistem;
                if ($selisih > 0) {
                    Kasbon::create([
                        'proyek_id' => $proyek->id,
                        'pegawai_id' => $pegawaiId,
                        'tanggal' => date('Y-m-d'),
                        'nominal' => $selisih,
                        'keterangan' => 'Sisa hutang / Kelebihan bayar dari gajian massal tanggal ' . date('d/m/Y'),
                        'status' => 'Belum Lunas',
                    ]);
                }

                // Tambahkan ke total uang tunai yang harus disiapkan
                $totalKasKeluar += $nominalDibayar;
            }

            // 4. CATAT PENGELUARAN KE BUKU KAS PROYEK
            if ($totalKasKeluar > 0) {

                // Proses upload bukti file jika ada
                $pathBukti = null;
                if ($request->hasFile('bukti_file')) {
                    $pathBukti = $request->file('bukti_file')->store('bukti_keuangan', 'public');
                }

                KeuanganProyek::create([
                    'proyek_id' => $proyek->id,
                    'tipe' => 'Pengeluaran',
                    'kategori' => 'Upah Tukang',
                    'nominal' => $totalKasKeluar,
                    'tanggal' => date('Y-m-d'),
                    'keterangan' => 'Pembayaran Gaji Massal Tukang (Periode: ' . Carbon::parse($periodeStart)->format('d/m/y') . ' s/d ' . Carbon::parse($periodeEnd)->format('d/m/y') . ')',
                    'bukti_file' => $pathBukti, // <--- Foto/Dokumen disimpan di sini
                ]);
            }
        });

        // 5. REDIRECT: Arahkan kembali ke halaman Keuangan Proyek dengan pesan sukses
        return redirect()->route('proyek.keuangan', $proyek->id)->with('success', 'Gaji massal berhasil dibayarkan, absensi telah digembok, dan saldo kas proyek telah dipotong!');
    }

    public function unduhPdf($id)
    {
        $proyek = Proyek::with('keuangans')->findOrFail($id);

        // Hitung total pemasukan dan pengeluaran secara akurat menggunakan kolom 'tipe'
        $totalPemasukan = $proyek->keuangans->where('tipe', 'Pemasukan')->sum('nominal');
        $totalPengeluaran = $proyek->keuangans->where('tipe', 'Pengeluaran')->sum('nominal');
        $kasTersedia = $totalPemasukan - $totalPengeluaran;

        // Data yang akan dikirim ke view PDF
        $data = [
            'proyek' => $proyek,
            'totalAnggaran' => $proyek->anggaran,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'kasTersedia' => $kasTersedia,
            'tanggal' => now()->translatedFormat('d F Y')
        ];

        $pdf = Pdf::loadView('pdf.laporan-keuangan', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Keuangan-' . $proyek->nama_proyek . '.pdf');
    }
}
