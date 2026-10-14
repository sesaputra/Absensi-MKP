<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\ItemPekerjaan;
use App\Models\LaporanHarian;
use App\Models\Proyek;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AbsensiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */

    /**
     * Pastikan user adalah Pengawas.
     */
    private function authorizePengawas(): void
    {
        abort_unless(
            Auth::check()
                && Auth::user()->role === 'pengawas',
            403,
            'Anda tidak memiliki akses sebagai Pengawas.'
        );
    }
    /**
     * Pastikan user adalah Admin atau Super Admin.
     */
    private function authorizeAdmin(): void
    {
        abort_unless(
            Auth::check()
                && in_array(
                    Auth::user()->role,
                    ['admin', 'super_admin'],
                    true
                ),
            403,
            'Anda tidak memiliki akses sebagai Admin.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PROJECT VALIDATION
    |--------------------------------------------------------------------------
    */

    /**
     * Pastikan proyek masih aktif.
     */
    private function ensureProyekAktif(Proyek $proyek): void
    {
        abort_unless(
            in_array($proyek->status, ['Akan Dimulai', 'Berjalan'], true),
            422,
            'Proyek sudah tidak aktif.'
        );
    }

    /**
     * Pastikan hari ini berada dalam periode proyek.
     */
    private function ensureTanggalProyekAktif(Proyek $proyek): void
    {
        $hariIni = Carbon::today();

        if ($proyek->tanggal_mulai) {
            $tanggalMulai = Carbon::parse($proyek->tanggal_mulai)->startOfDay();

            abort_if(
                $hariIni->lt($tanggalMulai),
                422,
                'Proyek ini baru akan dimulai pada '
                    . $tanggalMulai->translatedFormat('d F Y')
                    . '.'
            );
        }

        if ($proyek->tanggal_selesai) {
            $tanggalSelesai = Carbon::parse($proyek->tanggal_selesai)->startOfDay();

            abort_if(
                $hariIni->gt($tanggalSelesai),
                422,
                'Periode proyek ini sudah berakhir pada '
                    . $tanggalSelesai->translatedFormat('d F Y')
                    . '.'
            );
        }
    }

    /**
     * Pastikan user yang sedang login memiliki data Pegawai.
     */
    private function getPegawaiPengawas()
    {
        $user = Auth::user();

        abort_unless(
            $user && $user->pegawai,
            422,
            'Akun Pengawas belum terhubung dengan data Pegawai.'
        );

        return $user->pegawai;
    }

    /**
     * Pastikan Pengawas merupakan anggota proyek.
     */
    private function ensurePengawasDalamProyek(Proyek $proyek): void
    {
        $pegawai = $this->getPegawaiPengawas();

        $terdaftar = $proyek->pegawais()
            ->where('pegawais.id', $pegawai->id)
            ->exists();

        abort_unless(
            $terdaftar,
            403,
            'Anda tidak terdaftar sebagai anggota proyek ini.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE VALIDATION
    |--------------------------------------------------------------------------
    */

    /**
     * Ambil seluruh pegawai aktif yang terdaftar pada proyek.
     */
    private function getPegawaiAktifProyek(Proyek $proyek)
    {
        return $proyek->pegawais()
            ->where('pegawais.status', 'aktif')
            ->whereHas('jabatan')
            ->get();
    }

    /**
     * Validasi ID pegawai yang dikirim dari form.
     *
     * Selain memastikan ID valid, method ini juga memastikan
     * pegawai masih aktif dan memang anggota proyek.
     */
    private function validatePegawaiAbsensi(
        Proyek $proyek,
        array $absensi
    ): void {
        $pegawaiProyek = $this->getPegawaiAktifProyek($proyek);

        $allowedIds = $pegawaiProyek
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values();

        $submittedIds = collect(array_keys($absensi))
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        $invalidIds = $submittedIds->diff($allowedIds);

        abort_if(
            $invalidIds->isNotEmpty(),
            422,
            'Terdapat pegawai yang tidak terdaftar atau sudah tidak aktif pada proyek ini.'
        );

        /*
         * Semua pegawai aktif proyek wajib mempunyai status absensi.
         *
         * Pegawai yang tidak masuk tetap harus dikirim sebagai:
         * Sakit / Izin / Alfa.
         */
        $missingIds = $allowedIds->diff($submittedIds);

        abort_if(
            $missingIds->isNotEmpty(),
            422,
            'Data absensi belum lengkap. Pastikan seluruh pegawai aktif proyek sudah diisi.'
        );
    }

    /**
     * Validasi bahwa absensi sore benar-benar berasal dari
     * data absensi pagi yang sudah dibuat.
     */
    private function ensureAbsensiSesuaiLaporan(
        LaporanHarian $laporan,
        array $absensi
    ): void {
        $existingIds = $laporan->absensis()
            ->pluck('pegawai_id')
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        $submittedIds = collect(array_keys($absensi))
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        $missingFromReport = $submittedIds->diff($existingIds);
        $missingFromRequest = $existingIds->diff($submittedIds);

        abort_if(
            $missingFromReport->isNotEmpty()
                || $missingFromRequest->isNotEmpty(),
            422,
            'Data absensi sore tidak sesuai dengan data absensi pagi.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE / ABSENSI PAGI & SORE
    |--------------------------------------------------------------------------
    */

    /**
     * Form absensi.
     */
    public function create(Proyek $proyek)
    {
        $this->authorizePengawas();

        $this->ensureProyekAktif($proyek);
        $this->ensureTanggalProyekAktif($proyek);
        $this->ensurePengawasDalamProyek($proyek);

        $tanggalHariIni = Carbon::today()->format('Y-m-d');

        $proyek->load([
            'pegawais' => function ($query) {
                $query
                    ->where('pegawais.status', 'aktif')
                    ->with('jabatan');
            },
            'itemPekerjaans',
        ]);

        $laporanHariIni = LaporanHarian::with('absensis')
            ->where('proyek_id', $proyek->id)
            ->where('tanggal', $tanggalHariIni)
            ->first();

        /*
         * Belum ada laporan:
         * tampilkan form absensi pagi.
         */
        if (!$laporanHariIni) {
            return view(
                'pengawas.absensi',
                compact('proyek')
            );
        }

        /*
         * Draft Pagi:
         * absensi pagi sudah dibuat,
         * lanjutkan ke form sore.
         */
        if ($laporanHariIni->status_validasi === 'Draft Pagi') {
            $dataAbsen = $laporanHariIni
                ->absensis
                ->keyBy('pegawai_id');

            return view(
                'pengawas.absensi-sore',
                compact(
                    'proyek',
                    'laporanHariIni',
                    'dataAbsen'
                )
            );
        }

        /*
         * Ditolak:
         *
         * Mandor/Pengawas boleh memperbaiki laporan
         * kemudian mengirimkannya kembali.
         */
        if ($laporanHariIni->status_validasi === 'Ditolak') {
            $dataAbsen = $laporanHariIni
                ->absensis
                ->keyBy('pegawai_id');

            return view(
                'pengawas.absensi-sore',
                compact(
                    'proyek',
                    'laporanHariIni',
                    'dataAbsen'
                )
            );
        }

        /*
         * Menunggu persetujuan.
         */
        if ($laporanHariIni->status_validasi === 'Menunggu Persetujuan') {
            return redirect()
                ->route('pengawas.dashboard')
                ->with(
                    'success',
                    'Laporan hari ini sedang menunggu persetujuan Admin.'
                );
        }

        /*
         * Sudah disetujui.
         */
        return redirect()
            ->route('pengawas.dashboard')
            ->with(
                'success',
                'Laporan & Absensi hari ini sudah disetujui Admin.'
            );
    }

    /**
     * Simpan absensi pagi.
     */
    public function store(Request $request, Proyek $proyek)
    {
        $this->authorizePengawas();

        $this->ensureProyekAktif($proyek);
        $this->ensureTanggalProyekAktif($proyek);
        $this->ensurePengawasDalamProyek($proyek);

        $tanggalHariIni = Carbon::today()->format('Y-m-d');

        /*
         * Validasi request.
         */
        $validated = $request->validate([
            'foto_pagi' => [
                'required',
                'image',
                'max:5120',
            ],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'waktu_absen' => [
                'required',
                'date',
            ],

            'absensi' => [
                'required',
                'array',
                'min:1',
            ],

            'absensi.*.status' => [
                'required',
                'in:Hadir,Sakit,Izin,Alfa',
            ],

            'absensi.*.keterangan' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        /*
         * Pastikan pegawai yang dikirim benar-benar
         * anggota aktif proyek.
         */
        $this->validatePegawaiAbsensi(
            $proyek,
            $validated['absensi']
        );

        /*
         * Pastikan belum ada laporan.
         *
         * DB unique constraint tetap menjadi lapisan
         * perlindungan utama terhadap race condition.
         */
        if (
            LaporanHarian::where('proyek_id', $proyek->id)
            ->where('tanggal', $tanggalHariIni)
            ->exists()
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Tim di proyek ini sudah memiliki laporan hari ini.'
                );
        }

        $pathFotoPagi = null;

        try {
            /*
             * Simpan file sebelum transaction,
             * tetapi akan dihapus jika transaction gagal.
             */
            $pathFotoPagi = $request
                ->file('foto_pagi')
                ->store(
                    'bukti_lapangan',
                    'public'
                );

            DB::transaction(function () use (
                $validated,
                $proyek,
                $tanggalHariIni,
                $pathFotoPagi
            ) {
                $pegawaiPengawas = $this->getPegawaiPengawas();

                $laporan = LaporanHarian::create([
                    'proyek_id' => $proyek->id,
                    'pegawai_id' => $pegawaiPengawas->id,
                    'tanggal' => $tanggalHariIni,
                    'foto_pagi' => $pathFotoPagi,
                    'foto_sore' => null,
                    'kegiatan' => null,
                    'progres_snapshot' => null,
                    'latitude' => $validated['latitude'],
                    'longitude' => $validated['longitude'],
                    'waktu_absen' => $validated['waktu_absen'],
                    'status_validasi' => 'Draft Pagi',
                ]);

                foreach ($validated['absensi'] as $pegawaiId => $data) {
                    Absensi::create([
                        'laporan_harian_id' => $laporan->id,
                        'proyek_id' => $proyek->id,
                        'pegawai_id' => (int) $pegawaiId,
                        'tanggal' => $tanggalHariIni,

                        'status' => $data['status'],

                        'durasi' => $data['status'] === 'Hadir'
                            ? 'Full'
                            : null,

                        'keterangan' => $data['keterangan'] ?? null,

                        'status_validasi' => 'Draft Pagi',

                        'status_pembayaran' => 'Belum Dibayar',
                    ]);
                }
            });
        } catch (QueryException $e) {
            /*
             * Kemungkinan besar terjadi karena laporan
             * dibuat oleh request lain pada waktu yang sama.
             */
            if ($pathFotoPagi) {
                Storage::disk('public')->delete($pathFotoPagi);
            }

            if ($e->getCode() === '23000') {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Laporan absensi hari ini sudah dibuat oleh pengguna lain.'
                    );
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Gagal menyimpan absensi pagi.'
                );
        } catch (Throwable $e) {
            if ($pathFotoPagi) {
                Storage::disk('public')->delete($pathFotoPagi);
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menyimpan absensi pagi.'
                );
        }

        return redirect()
            ->route('pengawas.dashboard')
            ->with(
                'success',
                'Absen Pagi + Foto GPS berhasil! Lanjutkan progres nanti sore.'
            );
    }

    /**
     * Simpan laporan sore + progres + absensi final.
     */

    public function storeSore(
        Request $request,
        Proyek $proyek,
        ?LaporanHarian $laporan = null
    ) {
        $this->authorizePengawas();

        $isSusulan = $laporan !== null;

        /*
    |--------------------------------------------------------------------------
    | 1. VALIDASI AKSES DAN LAPORAN
    |--------------------------------------------------------------------------
    */

        if ($isSusulan) {
            $pegawai = $this->getPegawaiPengawas();

            // Pastikan laporan milik proyek dan Pengawas yang berwenang.
            abort_unless(
                (int) $laporan->proyek_id === (int) $proyek->id
                    && (int) $laporan->pegawai_id === (int) $pegawai->id
                    && $proyek->pegawais()
                    ->where('pegawais.id', $pegawai->id)
                    ->exists(),
                403,
                'Anda tidak memiliki akses ke laporan ini.'
            );

            // Laporan susulan hanya berlaku untuk tanggal sebelumnya.
            abort_unless(
                Carbon::parse($laporan->tanggal)->lt(Carbon::today()),
                422,
                'Laporan ini bukan laporan susulan.'
            );

            $laporan->load('absensis');

            // Hanya laporan pagi yang belum selesai atau ditolak yang dapat diubah.
            abort_unless(
                in_array(
                    $laporan->status_validasi,
                    ['Draft Pagi', 'Ditolak'],
                    true
                ),
                422,
                'Laporan ini sudah dikirim atau tidak dapat diubah.'
            );
        } else {
            // Pertahankan validasi untuk absensi sore biasa.
            $this->ensureProyekAktif($proyek);
            $this->ensureTanggalProyekAktif($proyek);
            $this->ensurePengawasDalamProyek($proyek);

            $tanggalHariIni = Carbon::today()->format('Y-m-d');

            $laporan = LaporanHarian::with('absensis')
                ->where('proyek_id', $proyek->id)
                ->whereDate('tanggal', $tanggalHariIni)
                ->first();

            abort_if(
                !$laporan,
                404,
                'Laporan absensi pagi hari ini belum dibuat.'
            );

            // Jangan memproses laporan hari ini yang sudah dikirim.
            abort_unless(
                in_array(
                    $laporan->status_validasi,
                    ['Draft Pagi', 'Ditolak'],
                    true
                ),
                422,
                'Laporan sore sudah dikirim atau tidak dapat diubah.'
            );
        }

        /*
    |--------------------------------------------------------------------------
    | 2. VALIDASI INPUT
    |--------------------------------------------------------------------------
    */

        $validated = $request->validate([
            'foto_sore' => [
                'required',
                'image',
                'max:5120',
            ],

            'kegiatan' => [
                'required',
                'string',
            ],

            'progres' => [
                'nullable',
                'array',
            ],

            'progres.*' => [
                'numeric',
                'min:0',
                'max:100',
            ],

            'absensi' => [
                'required',
                'array',
                'min:1',
            ],

            'absensi.*.status' => [
                'required',
                'in:Hadir,Sakit,Izin,Alfa',
            ],

            'absensi.*.durasi' => [
                'nullable',
                'in:Full,Setengah Hari',
            ],

            'absensi.*.keterangan' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        /*
    |--------------------------------------------------------------------------
    | 3. VALIDASI KESESUAIAN PEGAWAI
    |--------------------------------------------------------------------------
    */

        if (!$isSusulan) {
            $this->validatePegawaiAbsensi(
                $proyek,
                $validated['absensi']
            );
        }

        $this->ensureAbsensiSesuaiLaporan(
            $laporan,
            $validated['absensi']
        );

        /*
    |--------------------------------------------------------------------------
    | 4. VALIDASI ITEM PEKERJAAN
    |--------------------------------------------------------------------------
    */

        $semuaItemPekerjaan = ItemPekerjaan::where(
            'proyek_id',
            $proyek->id
        )->get();

        $allowedItemIds = $semuaItemPekerjaan
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->values();

        $submittedProgressIds = collect(
            array_keys($validated['progres'] ?? [])
        )
            ->map(fn($id) => (string) $id)
            ->unique()
            ->values();

        $invalidProgressIds = $submittedProgressIds
            ->diff($allowedItemIds);

        abort_if(
            $invalidProgressIds->isNotEmpty(),
            422,
            'Terdapat item pekerjaan yang tidak terdaftar pada proyek ini.'
        );

        /*
    |--------------------------------------------------------------------------
    | 5. SIMPAN FOTO SORE
    |--------------------------------------------------------------------------
    */

        $pathFotoSore = null;

        try {
            $pathFotoSore = $request
                ->file('foto_sore')
                ->store('bukti_lapangan', 'public');

            if (!$pathFotoSore) {
                throw new \RuntimeException(
                    'Foto sore gagal disimpan.'
                );
            }

            /*
        |--------------------------------------------------------------------------
        | 6. SIMPAN LAPORAN DALAM TRANSAKSI
        |--------------------------------------------------------------------------
        */

            DB::transaction(function () use (
                $validated,
                $proyek,
                $laporan,
                $pathFotoSore,
                $semuaItemPekerjaan,
                $isSusulan
            ) {
                $kegiatanFinal = $validated['kegiatan'];

                /*
             * ABSENSI SORE BIASA
             *
             * Progres proyek diperbarui hanya untuk laporan hari ini.
             */
                if (!$isSusulan) {
                    $catatanProgres = '';
                    $snapshotRiwayat = [];

                    foreach ($semuaItemPekerjaan as $item) {
                        $nilaiProgresBaru =
                            $validated['progres'][$item->id]
                            ?? $item->progres_sekarang;

                        if (
                            (float) $item->progres_sekarang
                            !== (float) $nilaiProgresBaru
                        ) {
                            $catatanProgres .=
                                "\n- "
                                . $item->nama_pekerjaan
                                . ' : '
                                . $item->progres_sekarang
                                . '% -> '
                                . $nilaiProgresBaru
                                . '%';

                            $item->update([
                                'progres_sekarang' => $nilaiProgresBaru,
                            ]);
                        }

                        $snapshotRiwayat[] = [
                            'item_pekerjaan_id' => $item->id,
                            'nama_pekerjaan' => $item->nama_pekerjaan,
                            'progres' => (float) $nilaiProgresBaru,
                        ];
                    }

                    // Hindari menumpuk catatan progres lama.
                    $bagianAtas = explode(
                        'PENCAPAIAN PROGRES HARIAN:',
                        $kegiatanFinal
                    )[0];

                    if ($catatanProgres !== '') {
                        $kegiatanFinal =
                            trim($bagianAtas)
                            . "\n\nPENCAPAIAN PROGRES HARIAN:"
                            . $catatanProgres;
                    }

                    $laporan->update([
                        'foto_sore' => $pathFotoSore,
                        'kegiatan' => $kegiatanFinal,
                        'progres_snapshot' => $snapshotRiwayat,
                        'status_validasi' => 'Menunggu Persetujuan',
                    ]);
                } else {
                    /*
                 * ABSENSI SORE SUSULAN
                 *
                 * Jangan memperbarui progres proyek terkini.
                 * Jangan menimpa progres_snapshot historis.
                 */
                    $laporan->update([
                        'foto_sore' => $pathFotoSore,
                        'kegiatan' => $kegiatanFinal,
                        'status_validasi' => 'Menunggu Persetujuan',
                    ]);
                }

                /*
            |--------------------------------------------------------------------------
            | 7. PERBARUI ABSENSI PEGAWAI
            |--------------------------------------------------------------------------
            */

                foreach ($validated['absensi'] as $pegawaiId => $data) {
                    $durasiFinal = $data['status'] === 'Hadir'
                        ? ($data['durasi'] ?? 'Full')
                        : null;

                    $jumlahDiubah = $laporan->absensis()
                        ->where('pegawai_id', (int) $pegawaiId)
                        ->update([
                            'status' => $data['status'],
                            'durasi' => $durasiFinal,
                            'keterangan' => $data['keterangan'] ?? null,
                            'status_validasi' => 'Menunggu Persetujuan',
                        ]);

                    // Mencegah data pegawai di luar laporan ikut diproses.
                    if ($jumlahDiubah === 0) {
                        throw new \RuntimeException(
                            'Data absensi pegawai tidak ditemukan dalam laporan.'
                        );
                    }
                }
            });
        } catch (Throwable $e) {
            if ($pathFotoSore) {
                Storage::disk('public')->delete($pathFotoSore);
            }

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menyimpan laporan sore. Silakan periksa kembali data Anda.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | 8. REDIRECT SETELAH BERHASIL
    |--------------------------------------------------------------------------
    */

        return redirect()
            ->route(
                $isSusulan
                    ? 'pengawas.absensi-tertunda'
                    : 'pengawas.dashboard'
            )
            ->with(
                'success',
                $isSusulan
                    ? 'Absensi sore susulan berhasil dikirim ke Admin.'
                    : 'Laporan final, foto sore, dan absensi berhasil dikirim ke Admin.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - GLOBAL ATTENDANCE
    |--------------------------------------------------------------------------
    */

    /**
     * Halaman manajemen absensi global.
     */
    public function indexAbsensiGlobal(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'filter_tanggal' => [
                'nullable',
                'date',
            ],
        ]);

        $tanggalFilter =
            $validated['filter_tanggal']
            ?? Carbon::today()->format('Y-m-d');

        /*
|--------------------------------------------------------------------------
| Laporan yang menunggu persetujuan
|--------------------------------------------------------------------------
*/
        $draftLaporan = LaporanHarian::with([
            'proyek',
            'pegawai.user',
            'absensis.pegawai',
        ])
            ->where('status_validasi', 'Menunggu Persetujuan')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        /*
|--------------------------------------------------------------------------
| Laporan yang sudah disetujui berdasarkan tanggal filter
|--------------------------------------------------------------------------
*/
        $laporanValid = LaporanHarian::with([
            'proyek',
            'pegawai.user',
            'absensis.pegawai',
        ])
            ->whereDate('tanggal', $tanggalFilter)
            ->where('status_validasi', 'Disetujui')
            ->orderBy('tanggal', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        return view(
            'admin.manajemen-absensi',
            compact(
                'draftLaporan',
                'laporanValid',
                'tanggalFilter'
            )
        );
    }

    /**
     * Normalisasi tanggal dari URL menjadi Y-m-d.
     * Menerima "2026-10-08" maupun "2026-10-08 00:00:00".
     */
    private function normalisasiTanggal(string $tanggal): string
    {
        try {
            return Carbon::parse($tanggal)->format('Y-m-d');
        } catch (Throwable $e) {
            abort(422, 'Format tanggal tidak valid.');
        }
    }

    public function setujuiAbsensi($proyek, $tanggal)
    {
        $this->authorizeAdmin();

        $tanggalValid = $this->normalisasiTanggal($tanggal);

        $laporan = LaporanHarian::where('proyek_id', $proyek)
            ->whereDate('tanggal', $tanggalValid)
            ->first();

        abort_if(!$laporan, 404, 'Laporan harian tidak ditemukan.');

        abort_unless(
            $laporan->status_validasi === 'Menunggu Persetujuan',
            422,
            'Laporan ini tidak sedang menunggu persetujuan.'
        );

        DB::transaction(function () use ($laporan) {
            $laporan->update(['status_validasi' => 'Disetujui']);

            Absensi::where('laporan_harian_id', $laporan->id)
                ->update(['status_validasi' => 'Disetujui']);
        });

        // Redirect eksplisit, BUKAN back()
        return redirect()
            ->route('admin.absensi.index', ['filter_tanggal' => $tanggalValid])
            ->with('success', 'Laporan Harian, Foto, dan Data Absensi berhasil disetujui!');
    }

    public function tolakAbsensi($proyek, $tanggal)
    {
        $this->authorizeAdmin();

        $tanggalValid = $this->normalisasiTanggal($tanggal);

        $laporan = LaporanHarian::where('proyek_id', $proyek)
            ->whereDate('tanggal', $tanggalValid)
            ->first();

        abort_if(!$laporan, 404, 'Laporan harian tidak ditemukan.');

        abort_unless(
            $laporan->status_validasi === 'Menunggu Persetujuan',
            422,
            'Laporan ini tidak sedang menunggu persetujuan.'
        );

        DB::transaction(function () use ($laporan) {
            $laporan->update(['status_validasi' => 'Ditolak']);

            Absensi::where('laporan_harian_id', $laporan->id)
                ->update(['status_validasi' => 'Ditolak']);
        });

        return redirect()
            ->route('admin.absensi.index')
            ->with('error', 'Laporan dan Absensi ditolak. Pengawas dapat memperbaikinya dan mengirim ulang.');
    }
    /*
    |--------------------------------------------------------------------------
    | ADMIN - DETAIL ABSENSI PROYEK
    |--------------------------------------------------------------------------
    */

    /**
     * Detail riwayat absensi sebuah proyek.
     */
    public function detailAbsensi(
        Request $request,
        Proyek $proyek
    ) {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'filter_bulan' => [
                'nullable',
                'date_format:Y-m',
            ],
        ]);

        $bulanFilter =
            $validated['filter_bulan']
            ?? Carbon::now()->format('Y-m');

        /*
         * Buat tanggal awal dan akhir bulan.
         */
        $tanggalAwal = Carbon::createFromFormat(
            'Y-m',
            $bulanFilter
        )->startOfMonth();

        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        $riwayatLaporan = LaporanHarian::with([
            'pegawai.user',
            'absensis.pegawai',
        ])
            ->where('proyek_id', $proyek->id)
            ->whereBetween('tanggal', [
                $tanggalAwal->format('Y-m-d'),
                $tanggalAkhir->format('Y-m-d'),
            ])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view(
            'admin.detail-absensi-proyek',
            compact(
                'proyek',
                'riwayatLaporan',
                'bulanFilter'
            )
        );
    }

    /**
     * Daftar absensi sore yang belum diselesaikan.
     */
    public function indexAbsensiTertunda()
    {
        $this->authorizePengawas();

        $pegawai = $this->getPegawaiPengawas();

        $proyekIds = $pegawai->proyeks()
            ->pluck('proyeks.id');

        $laporanTertunda = LaporanHarian::with([
            'proyek',
            'absensis.pegawai',
        ])
            ->whereIn('proyek_id', $proyekIds)
            ->where('pegawai_id', $pegawai->id)
            ->whereDate('tanggal', '<', Carbon::today())
            ->whereIn('status_validasi', [
                'Draft Pagi',
                'Ditolak',
            ])
            ->orderBy('tanggal')
            ->get();

        return view(
            'pengawas.absensi-tertunda',
            compact('laporanTertunda')
        );
    }

    /**
     * Membuka form absensi sore susulan.
     */
    public function editSoreSusulan(
        Proyek $proyek,
        LaporanHarian $laporan
    ) {
        $this->authorizePengawas();

        $pegawai = $this->getPegawaiPengawas();

        abort_unless(
            $laporan->proyek_id === $proyek->id
                && $laporan->pegawai_id === $pegawai->id
                && $proyek->pegawais()
                ->where('pegawais.id', $pegawai->id)
                ->exists(),
            403,
            'Anda tidak memiliki akses ke laporan ini.'
        );

        abort_unless(
            Carbon::parse($laporan->tanggal)->lt(Carbon::today()),
            422,
            'Laporan ini bukan laporan dari hari sebelumnya.'
        );

        abort_unless(
            in_array(
                $laporan->status_validasi,
                ['Draft Pagi', 'Ditolak'],
                true
            ),
            422,
            'Laporan ini tidak dapat dilengkapi.'
        );

        $laporan->load([
            'absensis.pegawai.jabatan',
        ]);

        $dataAbsen = $laporan->absensis
            ->keyBy('pegawai_id');

        $laporanHariIni = $laporan;

        $proyek->load('itemPekerjaans');

        $isSusulan = true;

        return view(
            'pengawas.absensi-sore',
            compact(
                'proyek',
                'laporanHariIni',
                'dataAbsen',
                'isSusulan'
            )
        );
    }
}
