<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Proyek</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #334155;
            font-size: 11px;
            line-height: 1.4;
            position: relative; /* Penting agar watermark terkunci di area body */
        }

        /* --- CSS WATERMARK --- */
        .watermark {
            position: fixed;
            top: 35%;
            left: 15%;
            width: 70%;
            text-align: center;
            /* Memutar teks sebesar -45 derajat */
            -webkit-transform: rotate(-45deg);
            -ms-transform: rotate(-45deg);
            transform: rotate(-45deg);
            /* Membuat teks transparan/pudar agar tidak menutupi tulisan laporan */
            color: rgba(12, 35, 64, 0.05); /* Menggunakan warna brand #0c2340 dengan opacity 5% */
            font-size: 50px;
            font-weight: bold;
            text-transform: uppercase;
            z-index: -1000; /* Memastikan posisi di paling belakang */
            pointer-events: none;
        }

        .header {
            border-bottom: 2px solid #0c2340;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header h2 {
            color: #0c2340;
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0 0 0;
            color: #64748b;
            font-size: 10px;
        }
        /* Kotak Ringkasan Saldo (4 Kolom) */
        .summary-box {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .summary-box td {
            border: 1px solid #cbd5e1;
            padding: 6px;
            background-color: #f8fafc;
            text-align: center;
            width: 25%;
        }
        .summary-title {
            font-size: 8.5px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
        }
        .summary-value {
            font-size: 11px;
            font-weight: bold;
            color: #0c2340;
            margin-top: 2px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
        }
        table.data-table th {
            background-color: #0c2340;
            color: #ffffff;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            text-align: center;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        /* Area Tanda Tangan Dua Kolom */
        .signature-section {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .signature-section td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            font-size: 11px;
        }
    </style>
</head>
<body>

    <!-- ELEMEN WATERMARK -->
    <div class="watermark">
        CV Mahesa Karya Perdana
    </div>

    <!-- KOP LAPORAN -->
    <div class="header">
        <h2>CV Mahesa Karya Perdana</h2>
        <p>Laporan Arus Kas & Keuangan Proyek: <strong>{{ $proyek->nama_proyek }}</strong></p>
        <p>Dicetak pada: {{ $tanggal }}</p>
    </div>

    <!-- KOTAK RINGKASAN KEUANGAN (4 KOLOM) -->
    <table class="summary-box">
        <tr>
            <td>
                <div class="summary-title">Total Anggaran (RAB)</div>
                <div class="summary-value" style="color: #0c2340;">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-title">Total Pemasukan</div>
                <div class="summary-value" style="color: #059669;">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-title">Total Pengeluaran</div>
                <div class="summary-value" style="color: #dc2626;">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="summary-title">Kas Tersedia</div>
                <div class="summary-value" style="color: {{ $kasTersedia >= 0 ? '#0c2340' : '#dc2626' }};">Rp {{ number_format($kasTersedia, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <!-- TABEL UTAMA ARUS KAS -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 40%;">Uraian / Keterangan</th>
                <th style="width: 20%; text-align: right;">Pemasukan (Rp)</th>
                <th style="width: 20%; text-align: right;">Pengeluaran (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($proyek->keuangans as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->keterangan }}</td>
                
                <!-- Kolom Pemasukan -->
                <td class="text-right" style="color: #059669;">
                    @if($item->tipe == 'Pemasukan')
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>

                <!-- Kolom Pengeluaran -->
                <td class="text-right" style="color: #dc2626;">
                    @if($item->tipe == 'Pengeluaran')
                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="color: #94a3b8;">Belum ada riwayat transaksi keuangan untuk proyek ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #f1f5f9;">
                <td colspan="3" class="text-right">TOTAL KESELURUHAN:</td>
                <td class="text-right" style="color: #059669;">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
                <td class="text-right" style="color: #dc2626;">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- TANDA TANGAN DUA PIHAK -->
    <table class="signature-section">
        <tr>
            <td>
                <p>Mengetahui / Disetujui oleh,</p>
                <p><strong>Pemilik Proyek (Owner)</strong></p>
                <br><br><br>
                <p><strong>( _______________________ )</strong></p>
            </td>
            <td>
                <p>Tabanan, {{ $tanggal }}</p>
                <p><strong>CV Mahesa Karya Perdana</strong></p>
                <br><br><br>
                <p><strong>( _______________________ )</strong></p>
                <p>Pimpinan / Admin Proyek</p>
            </td>
        </tr>
    </table>

</body>
</html>