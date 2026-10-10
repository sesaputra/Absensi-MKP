<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kasbon;
use App\Models\KeuanganProyek;
use App\Models\Pegawai;
use App\Models\Proyek;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KeuanganProyekController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check() &&
                in_array(
                    Auth::user()->role,
                    ['admin', 'super_admin'],
                    true
                ),
            403
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI PROYEK
    |--------------------------------------------------------------------------
    */

    private function ensureProyekAktif(Proyek $proyek): void
    {
        abort_unless(
            in_array(
                $proyek->status,
                ['Akan Dimulai', 'Berjalan'],
                true
            ),
            422,
            'Proyek sudah tidak aktif.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI PERIODE PAYROLL
    |--------------------------------------------------------------------------
    */

    private function validatePayrollPeriod(
        Proyek $proyek,
        string $startDate,
        string $endDate
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Tanggal awal tidak boleh setelah tanggal akhir
        |--------------------------------------------------------------------------
        */

        if ($startDate > $endDate) {
            abort(
                422,
                'Tanggal awal periode tidak boleh setelah tanggal akhir.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Periode tidak boleh sebelum proyek dimulai
        |--------------------------------------------------------------------------
        */

        if (
            $proyek->tanggal_mulai &&
            $startDate < $proyek->tanggal_mulai->format('Y-m-d')
        ) {
            abort(
                422,
                'Tanggal awal periode upah tidak boleh sebelum tanggal mulai proyek.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Periode tidak boleh menggunakan tanggal masa depan
        |--------------------------------------------------------------------------
        */

        if ($endDate > now()->format('Y-m-d')) {
            abort(
                422,
                'Periode upah tidak boleh menggunakan tanggal yang akan datang.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PEGAWAI PROYEK
    |--------------------------------------------------------------------------
    */

    private function getPegawaiUntukProyek(
        Proyek $proyek,
        int $pegawaiId
    ): Pegawai {
        $pegawai = Pegawai::query()
            ->with('jabatan')
            ->whereKey($pegawaiId)
            ->where('status', 'aktif')
            ->first();

        abort_unless(
            $pegawai,
            422,
            'Pegawai tidak ditemukan atau sudah tidak aktif.'
        );

        /*
        |--------------------------------------------------------------------------
        | Pastikan pegawai terdaftar pada proyek
        |--------------------------------------------------------------------------
        */

        $terdaftarDiProyek = $proyek
            ->pegawais()
            ->whereKey($pegawai->id)
            ->exists();

        abort_unless(
            $terdaftarDiProyek,
            422,
            "Pegawai {$pegawai->nama} tidak terdaftar pada proyek ini."
        );

        /*
        |--------------------------------------------------------------------------
        | Pastikan memiliki jabatan
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $pegawai->jabatan,
            422,
            "Pegawai {$pegawai->nama} belum memiliki jabatan."
        );

        return $pegawai;
    }

    /*
    |--------------------------------------------------------------------------
    | HITUNG PAYROLL
    |--------------------------------------------------------------------------
    |
    | Semua perhitungan dasar payroll dilakukan melalui method ini.
    |
    */

    private function hitungPayroll(
        Proyek $proyek,
        Pegawai $pegawai,
        string $startDate,
        string $endDate
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Ambil absensi yang memenuhi syarat pembayaran
        |--------------------------------------------------------------------------
        */

        $absensis = Absensi::query()
            ->where('proyek_id', $proyek->id)
            ->where('pegawai_id', $pegawai->id)
            ->whereBetween(
                'tanggal',
                [$startDate, $endDate]
            )
            ->where('status', 'Hadir')
            ->where('status_validasi', 'Disetujui')
            ->where('status_pembayaran', 'Belum Dibayar')
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Hitung hari kerja
        |--------------------------------------------------------------------------
        */

        $hariFull = $absensis
            ->where('durasi', 'Full')
            ->count();

        $hariSetengah = $absensis
            ->where('durasi', 'Setengah Hari')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Ambil gaji harian
        |--------------------------------------------------------------------------
        */

        $gajiHarian = round(
            (float) ($pegawai->jabatan?->gaji_harian ?? 0),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Hitung upah kotor
        |--------------------------------------------------------------------------
        */

        $upahKotor = round(
            ($hariFull * $gajiHarian) +
                ($hariSetengah * ($gajiHarian / 2)),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Hitung total kasbon aktif
        |--------------------------------------------------------------------------
        */

        $totalKasbonAktif = round(
            (float) Kasbon::query()
                ->where('pegawai_id', $pegawai->id)
                ->where('proyek_id', $proyek->id)
                ->where('status', 'Belum Lunas')
                ->where('sisa_nominal', '>', 0)
                ->sum('sisa_nominal'),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Potongan kasbon maksimal sebesar upah kotor
        |--------------------------------------------------------------------------
        */

        $potonganKasbon = round(
            min(
                $totalKasbonAktif,
                $upahKotor
            ),
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Upah bersih
        |--------------------------------------------------------------------------
        */

        $upahBersih = round(
            $upahKotor - $potonganKasbon,
            2
        );

        return [
            'pegawai' => $pegawai,

            'absensis' => $absensis,

            'hari_full' => $hariFull,

            'hari_setengah' => $hariSetengah,

            'gaji_harian' => $gajiHarian,

            'upah_kotor' => $upahKotor,

            'total_kasbon_aktif' => $totalKasbonAktif,

            'potongan_kasbon' => $potonganKasbon,

            'upah_bersih' => $upahBersih,

            'attendance_ids' => $absensis
                ->pluck('id')
                ->values(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | BAYAR KASBON FIFO
    |--------------------------------------------------------------------------
    |
    | Kasbon terlama dibayar terlebih dahulu.
    |
    */

    private function bayarKasbonFIFO(
        Proyek $proyek,
        Pegawai $pegawai,
        float $nominal
    ): void {
        if ($nominal <= 0) {
            return;
        }

        $sisaPotongan = round($nominal, 2);

        /*
        |--------------------------------------------------------------------------
        | Lock kasbon agar tidak diproses bersamaan oleh transaksi lain
        |--------------------------------------------------------------------------
        */

        $kasbons = Kasbon::query()
            ->where('pegawai_id', $pegawai->id)
            ->where('proyek_id', $proyek->id)
            ->where('status', 'Belum Lunas')
            ->where('sisa_nominal', '>', 0)
            ->orderBy('tanggal')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        foreach ($kasbons as $kasbon) {
            if ($sisaPotongan <= 0) {
                break;
            }

            $sisaKasbon = round(
                (float) $kasbon->sisa_nominal,
                2
            );

            $potongan = min(
                $sisaKasbon,
                $sisaPotongan
            );

            $sisaKasbonBaru = round(
                $sisaKasbon - $potongan,
                2
            );

            $kasbon->update([
                'sisa_nominal' => $sisaKasbonBaru,

                'status' => $sisaKasbonBaru <= 0
                    ? 'Lunas'
                    : 'Belum Lunas',
            ]);

            $sisaPotongan = round(
                $sisaPotongan - $potongan,
                2
            );
        }

        abort_unless(
            $sisaPotongan <= 0,
            422,
            'Potongan kasbon tidak dapat diproses secara penuh.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | KEUANGAN PROYEK
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        Proyek $proyek
    ): View {
        $this->authorizeAdmin();

        $keuangans = $proyek
            ->keuangans()
            ->with('kasbon.pegawai')
            ->latest('tanggal')
            ->latest('id')
            ->get();

        $totalAnggaran = (float) $proyek->anggaran;

        $totalPemasukan = (float) $keuangans
            ->where('tipe', 'Pemasukan')
            ->sum('nominal');

        $totalPengeluaran = (float) $keuangans
            ->where('tipe', 'Pengeluaran')
            ->sum('nominal');

        /*
        |--------------------------------------------------------------------------
        | Saldo kas aktual
        |--------------------------------------------------------------------------
        */

        $kasTersedia = round(
            $totalPemasukan - $totalPengeluaran,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Persentase penggunaan berdasarkan pemasukan aktual
        |--------------------------------------------------------------------------
        */

        $persentasePenggunaan = $totalPemasukan > 0
            ? round(
                ($totalPengeluaran / $totalPemasukan) * 100,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Status kondisi kas
        |--------------------------------------------------------------------------
        */

        if ($kasTersedia <= 5_000_000) {
            $statusKas = 'Kritis';
        } elseif ($kasTersedia <= 20_000_000) {
            $statusKas = 'Waspada';
        } else {
            $statusKas = 'Aman';
        }

        return view(
            'Admin.laporan-keuangan',
            compact(
                'proyek',
                'keuangans',
                'totalAnggaran',
                'totalPemasukan',
                'totalPengeluaran',
                'kasTersedia',
                'persentasePenggunaan',
                'statusKas'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE TRANSAKSI KEUANGAN UMUM
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Proyek $proyek
    ): RedirectResponse {
        $this->authorizeAdmin();
        $this->ensureProyekAktif($proyek);

        $data = $request->validate([
            'tipe' => [
                'required',
                'in:Pemasukan,Pengeluaran',
            ],

            'kategori' => [
                'required',
                'string',
                'max:255',
            ],

            'nominal' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'tanggal' => [
                'required',
                'date',
            ],

            'keterangan' => [
                'required',
                'string',
                'max:2000',
            ],

            'bukti_file' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Transaksi tidak boleh sebelum proyek dimulai
        |--------------------------------------------------------------------------
        */

        if (
            $proyek->tanggal_mulai &&
            $data['tanggal'] < $proyek->tanggal_mulai->format('Y-m-d')
        ) {
            return back()->with(
                'error',
                'Tanggal transaksi tidak boleh sebelum tanggal mulai proyek.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Transaksi tidak boleh di masa depan
        |--------------------------------------------------------------------------
        */

        if ($data['tanggal'] > now()->format('Y-m-d')) {
            return back()->with(
                'error',
                'Tanggal transaksi tidak boleh menggunakan tanggal yang akan datang.'
            );
        }

        $buktiPath = null;

        try {
            DB::transaction(function () use (
                $request,
                $data,
                $proyek,
                &$buktiPath
            ) {
                /*
                |--------------------------------------------------------------------------
                | Upload bukti
                |--------------------------------------------------------------------------
                */

                if ($request->hasFile('bukti_file')) {
                    $buktiPath = $request
                        ->file('bukti_file')
                        ->store(
                            'bukti-keuangan',
                            'public'
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | Simpan transaksi
                |--------------------------------------------------------------------------
                */

                KeuanganProyek::create([
                    'proyek_id' => $proyek->id,

                    'kasbon_id' => null,

                    'tipe' => $data['tipe'],

                    'kategori' => $data['kategori'],

                    'nominal' => $data['nominal'],

                    'tanggal' => $data['tanggal'],

                    'keterangan' => $data['keterangan'],

                    'bukti_file' => $buktiPath,
                ]);
            });

            return back()->with(
                'success',
                'Transaksi keuangan berhasil dicatat.'
            );
        } catch (\Throwable $e) {
            if ($buktiPath) {
                Storage::disk('public')->delete($buktiPath);
            }

            report($e);

            return back()->with(
                'error',
                'Transaksi gagal disimpan.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PREVIEW GAJI
    |--------------------------------------------------------------------------
    */

    public function previewGaji(
        Request $request,
        Proyek $proyek
    ) {
        $this->authorizeAdmin();
        $this->ensureProyekAktif($proyek);

        $data = $request->validate([
            'pegawai_id' => [
                'required',
                'integer',
                'exists:pegawais,id',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],
        ]);

        $this->validatePayrollPeriod(
            $proyek,
            $data['start_date'],
            $data['end_date']
        );

        $pegawai = $this->getPegawaiUntukProyek(
            $proyek,
            (int) $data['pegawai_id']
        );

        $hasil = $this->hitungPayroll(
            $proyek,
            $pegawai,
            $data['start_date'],
            $data['end_date']
        );

        return response()->json([
            'success' => true,

            'data' => [
                'pegawai_id' =>
                $pegawai->id,

                'nama' =>
                $pegawai->nama,

                'jabatan' =>
                $pegawai->jabatan->nama_jabatan,

                'gaji_harian' =>
                $hasil['gaji_harian'],

                'hari_full' =>
                $hasil['hari_full'],

                'hari_setengah' =>
                $hasil['hari_setengah'],

                'upah_kotor' =>
                $hasil['upah_kotor'],

                'total_kasbon_aktif' =>
                $hasil['total_kasbon_aktif'],

                'potongan_kasbon' =>
                $hasil['potongan_kasbon'],

                'upah_bersih' =>
                $hasil['upah_bersih'],

                'attendance_ids' =>
                $hasil['attendance_ids'],
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | BAYAR GAJI SATU PEGAWAI
    |--------------------------------------------------------------------------
    */

    public function bayarGaji(
        Request $request,
        Proyek $proyek
    ): RedirectResponse {
        $this->authorizeAdmin();
        $this->ensureProyekAktif($proyek);

        $data = $request->validate([
            'pegawai_id' => [
                'required',
                'integer',
                'exists:pegawais,id',
            ],

            'nominal' => [
                'required',
                'numeric',
                'min:0',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'bukti_file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ]);

        $this->validatePayrollPeriod(
            $proyek,
            $data['start_date'],
            $data['end_date']
        );

        $pegawai = $this->getPegawaiUntukProyek(
            $proyek,
            (int) $data['pegawai_id']
        );

        $buktiPath = null;

        try {
            DB::transaction(function () use (
                $request,
                $data,
                $proyek,
                $pegawai,
                &$buktiPath
            ) {
                /*
                |--------------------------------------------------------------------------
                | LOCK ABSENSI
                |--------------------------------------------------------------------------
                */

                $absensis = Absensi::query()
                    ->where('proyek_id', $proyek->id)
                    ->where('pegawai_id', $pegawai->id)
                    ->whereBetween(
                        'tanggal',
                        [
                            $data['start_date'],
                            $data['end_date'],
                        ]
                    )
                    ->where('status', 'Hadir')
                    ->where('status_validasi', 'Disetujui')
                    ->where('status_pembayaran', 'Belum Dibayar')
                    ->orderBy('tanggal')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                abort_if(
                    $absensis->isEmpty(),
                    422,
                    'Tidak ada absensi yang dapat dibayarkan pada periode tersebut.'
                );

                /*
                |--------------------------------------------------------------------------
                | Hitung ulang payroll
                |--------------------------------------------------------------------------
                */

                $hariFull = $absensis
                    ->where('durasi', 'Full')
                    ->count();

                $hariSetengah = $absensis
                    ->where('durasi', 'Setengah Hari')
                    ->count();

                $gajiHarian = round(
                    (float) $pegawai->jabatan->gaji_harian,
                    2
                );

                $upahKotor = round(
                    ($hariFull * $gajiHarian) +
                        ($hariSetengah * ($gajiHarian / 2)),
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | LOCK KASBON
                |--------------------------------------------------------------------------
                */

                $kasbons = Kasbon::query()
                    ->where('pegawai_id', $pegawai->id)
                    ->where('proyek_id', $proyek->id)
                    ->where('status', 'Belum Lunas')
                    ->where('sisa_nominal', '>', 0)
                    ->orderBy('tanggal')
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get();

                $totalKasbonAktif = round(
                    (float) $kasbons->sum('sisa_nominal'),
                    2
                );

                $potonganKasbon = round(
                    min(
                        $totalKasbonAktif,
                        $upahKotor
                    ),
                    2
                );

                $upahBersih = round(
                    $upahKotor - $potonganKasbon,
                    2
                );

                $nominalDibayar = round(
                    (float) $data['nominal'],
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | Tidak boleh membayar di bawah hak bersih
                |--------------------------------------------------------------------------
                */

                abort_if(
                    $nominalDibayar < $upahBersih,
                    422,
                    'Nominal pembayaran tidak boleh lebih kecil dari hak bersih pegawai.'
                );

                /*
                |--------------------------------------------------------------------------
                | Tandai absensi sudah dibayar
                |--------------------------------------------------------------------------
                |
                | PENTING:
                | Migration menggunakan "Dibayar", bukan "Sudah Dibayar".
                |
                */

                Absensi::query()
                    ->whereIn(
                        'id',
                        $absensis->pluck('id')
                    )
                    ->update([
                        'status_pembayaran' => 'Dibayar',
                    ]);

                /*
                |--------------------------------------------------------------------------
                | Bayar kasbon FIFO
                |--------------------------------------------------------------------------
                */

                $this->bayarKasbonFIFO(
                    $proyek,
                    $pegawai,
                    $potonganKasbon
                );

                /*
                |--------------------------------------------------------------------------
                | Kelebihan pembayaran menjadi kasbon baru
                |--------------------------------------------------------------------------
                */

                $kasbonBaru = round(
                    $nominalDibayar - $upahBersih,
                    2
                );

                if ($kasbonBaru > 0) {
                    Kasbon::create([
                        'pegawai_id' =>
                        $pegawai->id,

                        'proyek_id' =>
                        $proyek->id,

                        'tanggal' =>
                        now()->format('Y-m-d'),

                        'nominal' =>
                        $kasbonBaru,

                        'sisa_nominal' =>
                        $kasbonBaru,

                        'keterangan' =>
                        'Selisih pembayaran upah periode ' .
                            $data['start_date'] .
                            ' s.d. ' .
                            $data['end_date'],

                        'status' =>
                        'Belum Lunas',
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Upload bukti pembayaran
                |--------------------------------------------------------------------------
                */

                if ($request->hasFile('bukti_file')) {
                    $buktiPath = $request
                        ->file('bukti_file')
                        ->store(
                            'bukti-keuangan',
                            'public'
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | Catat CASH OUT aktual
                |--------------------------------------------------------------------------
                */

                KeuanganProyek::create([
                    'proyek_id' =>
                    $proyek->id,

                    'kasbon_id' =>
                    null,

                    'tipe' =>
                    'Pengeluaran',

                    'kategori' =>
                    'Upah Tukang',

                    'nominal' =>
                    $nominalDibayar,

                    'tanggal' =>
                    now()->format('Y-m-d'),

                    'keterangan' =>
                    'Pembayaran upah a.n ' .
                        $pegawai->nama .
                        ' periode ' .
                        $data['start_date'] .
                        ' s.d. ' .
                        $data['end_date'],

                    'bukti_file' =>
                    $buktiPath,
                ]);
            });

            return redirect()
                ->route(
                    'proyek.keuangan',
                    $proyek->id
                )
                ->with(
                    'success',
                    "Upah {$pegawai->nama} berhasil dibayarkan."
                );
        } catch (\Throwable $e) {
            if ($buktiPath) {
                Storage::disk('public')->delete($buktiPath);
            }

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage() ?: 'Pembayaran upah gagal diproses.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN REKAP / RINCIAN UPAH
    |--------------------------------------------------------------------------
    */

    public function payrollIndex(
        Request $request,
        Proyek $proyek
    ): View {
        $this->authorizeAdmin();
        $this->ensureProyekAktif($proyek);

        $data = $request->validate([
            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Default periode = 7 hari terakhir
        |--------------------------------------------------------------------------
        |
        | Tetapi tidak boleh mundur melewati tanggal mulai proyek.
        |
        */

        $defaultStartDate = now()
            ->subDays(6)
            ->startOfDay();

        if ($proyek->tanggal_mulai) {
            $tanggalMulaiProyek = $proyek
                ->tanggal_mulai
                ->copy()
                ->startOfDay();

            if (
                $tanggalMulaiProyek
                ->greaterThan($defaultStartDate)
            ) {
                $defaultStartDate = $tanggalMulaiProyek;
            }
        }

        $startDate = $data['start_date']
            ?? $defaultStartDate->format('Y-m-d');

        $endDate = $data['end_date']
            ?? now()->format('Y-m-d');

        $this->validatePayrollPeriod(
            $proyek,
            $startDate,
            $endDate
        );

        /*
        |--------------------------------------------------------------------------
        | Cari pegawai berdasarkan absensi yang memenuhi syarat
        |--------------------------------------------------------------------------
        */

        $absensis = Absensi::query()
            ->with('pegawai.jabatan')
            ->where('proyek_id', $proyek->id)
            ->whereBetween(
                'tanggal',
                [
                    $startDate,
                    $endDate,
                ]
            )
            ->where('status', 'Hadir')
            ->where('status_validasi', 'Disetujui')
            ->where('status_pembayaran', 'Belum Dibayar')
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ambil pegawai unik
        |--------------------------------------------------------------------------
        */

        $pegawaiIds = $absensis
            ->pluck('pegawai_id')
            ->unique()
            ->values();

        $rekapGaji = [];

        foreach ($pegawaiIds as $pegawaiId) {
            $pegawai = $absensis
                ->firstWhere(
                    'pegawai_id',
                    $pegawaiId
                )
                ?->pegawai;

            if (!$pegawai || !$pegawai->jabatan) {
                continue;
            }

            $hasil = $this->hitungPayroll(
                $proyek,
                $pegawai,
                $startDate,
                $endDate
            );

            if (
                $hasil['attendance_ids']->isEmpty()
            ) {
                continue;
            }

            $rekapGaji[$pegawaiId] = [
                'pegawai' =>
                $pegawai,

                'hari_full' =>
                $hasil['hari_full'],

                'hari_setengah' =>
                $hasil['hari_setengah'],

                'gaji_harian' =>
                $hasil['gaji_harian'],

                'upah_kotor' =>
                $hasil['upah_kotor'],

                'total_kasbon_aktif' =>
                $hasil['total_kasbon_aktif'],

                'potongan_kasbon' =>
                $hasil['potongan_kasbon'],

                'upah_bersih' =>
                $hasil['upah_bersih'],

                'id_absensi' =>
                $hasil['attendance_ids']->all(),
            ];
        }

        return view(
            'Admin.payroll',
            compact(
                'proyek',
                'rekapGaji',
                'startDate',
                'endDate'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PEMBAYARAN GAJI MASSAL
    |--------------------------------------------------------------------------
    */

    public function payrollStore(
        Request $request,
        Proyek $proyek
    ): RedirectResponse {
        $this->authorizeAdmin();
        $this->ensureProyekAktif($proyek);

        $data = $request->validate([
            'periode_start' => [
                'required',
                'date',
            ],

            'periode_end' => [
                'required',
                'date',
                'after_or_equal:periode_start',
            ],

            'pembayaran' => [
                'required',
                'array',
                'min:1',
            ],

            'pembayaran.*.pegawai_id' => [
                'required',
                'integer',
                'exists:pegawais,id',
            ],

            'pembayaran.*.nominal' => [
                'required',
                'numeric',
                'min:0',
            ],

            'bukti_file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ]);

        $this->validatePayrollPeriod(
            $proyek,
            $data['periode_start'],
            $data['periode_end']
        );

        $buktiPath = null;

        try {
            DB::transaction(function () use (
                $request,
                $data,
                $proyek,
                &$buktiPath
            ) {
                $pegawaiIdsDiproses = [];

                $totalCashOut = 0;

                foreach (
                    $data['pembayaran']
                    as $pembayaran
                ) {
                    $pegawaiId =
                        (int) $pembayaran['pegawai_id'];

                    /*
                    |--------------------------------------------------------------------------
                    | Cegah pegawai diproses dua kali
                    |--------------------------------------------------------------------------
                    */

                    abort_if(
                        in_array(
                            $pegawaiId,
                            $pegawaiIdsDiproses,
                            true
                        ),
                        422,
                        'Pegawai yang sama tidak boleh diproses lebih dari satu kali.'
                    );

                    $pegawaiIdsDiproses[] =
                        $pegawaiId;

                    /*
                    |--------------------------------------------------------------------------
                    | Validasi pegawai
                    |--------------------------------------------------------------------------
                    */

                    $pegawai =
                        $this->getPegawaiUntukProyek(
                            $proyek,
                            $pegawaiId
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | LOCK ABSENSI
                    |--------------------------------------------------------------------------
                    */

                    $absensis = Absensi::query()
                        ->where('proyek_id', $proyek->id)
                        ->where('pegawai_id', $pegawai->id)
                        ->whereBetween(
                            'tanggal',
                            [
                                $data['periode_start'],
                                $data['periode_end'],
                            ]
                        )
                        ->where('status', 'Hadir')
                        ->where('status_validasi', 'Disetujui')
                        ->where('status_pembayaran', 'Belum Dibayar')
                        ->orderBy('tanggal')
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get();

                    abort_if(
                        $absensis->isEmpty(),
                        422,
                        "Tidak ada absensi yang dapat dibayarkan untuk {$pegawai->nama}."
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Hitung ulang payroll
                    |--------------------------------------------------------------------------
                    */

                    $hariFull = $absensis
                        ->where('durasi', 'Full')
                        ->count();

                    $hariSetengah = $absensis
                        ->where('durasi', 'Setengah Hari')
                        ->count();

                    $gajiHarian = round(
                        (float) $pegawai
                            ->jabatan
                            ->gaji_harian,
                        2
                    );

                    $upahKotor = round(
                        ($hariFull * $gajiHarian) +
                            (
                                $hariSetengah *
                                ($gajiHarian / 2)
                            ),
                        2
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | LOCK KASBON
                    |--------------------------------------------------------------------------
                    */

                    $kasbons = Kasbon::query()
                        ->where('pegawai_id', $pegawai->id)
                        ->where('proyek_id', $proyek->id)
                        ->where('status', 'Belum Lunas')
                        ->where('sisa_nominal', '>', 0)
                        ->orderBy('tanggal')
                        ->orderBy('id')
                        ->lockForUpdate()
                        ->get();

                    $totalKasbonAktif = round(
                        (float) $kasbons->sum(
                            'sisa_nominal'
                        ),
                        2
                    );

                    $potonganKasbon = round(
                        min(
                            $totalKasbonAktif,
                            $upahKotor
                        ),
                        2
                    );

                    $upahBersih = round(
                        $upahKotor -
                            $potonganKasbon,
                        2
                    );

                    $nominalDibayar = round(
                        (float) $pembayaran['nominal'],
                        2
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Tidak boleh membayar di bawah hak bersih
                    |--------------------------------------------------------------------------
                    */

                    abort_if(
                        $nominalDibayar < $upahBersih,
                        422,
                        "Nominal pembayaran untuk {$pegawai->nama} tidak boleh lebih kecil dari hak bersih."
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Tandai absensi sebagai DIBAYAR
                    |--------------------------------------------------------------------------
                    */

                    Absensi::query()
                        ->whereIn(
                            'id',
                            $absensis->pluck('id')
                        )
                        ->update([
                            'status_pembayaran' =>
                            'Dibayar',
                        ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Potong kasbon FIFO
                    |--------------------------------------------------------------------------
                    */

                    $this->bayarKasbonFIFO(
                        $proyek,
                        $pegawai,
                        $potonganKasbon
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Kelebihan pembayaran menjadi kasbon baru
                    |--------------------------------------------------------------------------
                    */

                    $kasbonBaru = round(
                        $nominalDibayar -
                            $upahBersih,
                        2
                    );

                    if ($kasbonBaru > 0) {
                        Kasbon::create([
                            'pegawai_id' =>
                            $pegawai->id,

                            'proyek_id' =>
                            $proyek->id,

                            'tanggal' =>
                            now()->format('Y-m-d'),

                            'nominal' =>
                            $kasbonBaru,

                            'sisa_nominal' =>
                            $kasbonBaru,

                            'keterangan' =>
                            'Selisih pembayaran upah periode ' .
                                $data['periode_start'] .
                                ' s.d. ' .
                                $data['periode_end'],

                            'status' =>
                            'Belum Lunas',
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Akumulasi cash out aktual
                    |--------------------------------------------------------------------------
                    */

                    $totalCashOut = round(
                        $totalCashOut +
                            $nominalDibayar,
                        2
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Upload satu bukti pembayaran massal
                |--------------------------------------------------------------------------
                */

                if ($request->hasFile('bukti_file')) {
                    $buktiPath = $request
                        ->file('bukti_file')
                        ->store(
                            'bukti-keuangan',
                            'public'
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | Catat satu transaksi keuangan
                |--------------------------------------------------------------------------
                */

                KeuanganProyek::create([
                    'proyek_id' =>
                    $proyek->id,

                    'kasbon_id' =>
                    null,

                    'tipe' =>
                    'Pengeluaran',

                    'kategori' =>
                    'Upah Tukang',

                    'nominal' =>
                    $totalCashOut,

                    'tanggal' =>
                    now()->format('Y-m-d'),

                    'keterangan' =>
                    'Pembayaran upah massal periode ' .
                        $data['periode_start'] .
                        ' s.d. ' .
                        $data['periode_end'],

                    'bukti_file' =>
                    $buktiPath,
                ]);
            });

            return redirect()
                ->route(
                    'proyek.keuangan',
                    $proyek->id
                )
                ->with(
                    'success',
                    'Pembayaran upah berhasil diproses dan saldo kas proyek telah diperbarui.'
                );
        } catch (\Throwable $e) {
            if ($buktiPath) {
                Storage::disk('public')->delete(
                    $buktiPath
                );
            }

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage() ?: 'Pembayaran upah gagal diproses.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UNDUH PDF LAPORAN KEUANGAN
    |--------------------------------------------------------------------------
    */


    /*
|--------------------------------------------------------------------------
| UNDUH PDF LAPORAN KEUANGAN
|--------------------------------------------------------------------------
*/

    public function unduhPdf(Proyek $proyek)
    {
        $this->authorizeAdmin();

        $keuangans = $proyek
            ->keuangans()
            ->with('kasbon.pegawai')
            ->orderBy('tanggal')
            ->orderBy('id')
            ->get();

        $totalAnggaran = (float) ($proyek->anggaran ?? 0);

        $totalPemasukan = (float) $keuangans
            ->where('tipe', 'Pemasukan')
            ->sum('nominal');

        $totalPengeluaran = (float) $keuangans
            ->where('tipe', 'Pengeluaran')
            ->sum('nominal');

        $kasTersedia = round(
            $totalPemasukan - $totalPengeluaran,
            2
        );

        $tanggal = now()
            ->locale('id')
            ->translatedFormat('d F Y');

        $pdf = Pdf::loadView(
            'pdf.laporan-keuangan',
            compact(
                'proyek',
                'keuangans',
                'totalAnggaran',
                'totalPemasukan',
                'totalPengeluaran',
                'kasTersedia',
                'tanggal'
            )
        );

        return $pdf->download(
            'laporan-keuangan-' .
                \Illuminate\Support\Str::slug($proyek->nama_proyek) .
                '.pdf'
        );
    }
}
