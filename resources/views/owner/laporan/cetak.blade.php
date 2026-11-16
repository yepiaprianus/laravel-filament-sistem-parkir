<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Parkir ({{ $dariTanggal }} s/d {{ $sampaiTanggal }})</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 20px;
            color: #111;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header p {
            margin: 4px 0 0;
            color: #555;
            font-size: 13px;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 12px;
        }
        .summary-boxes {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        .box {
            flex: 1;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 4px;
            background: #f9f9f9;
        }
        .box span {
            display: block;
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
        }
        .box strong {
            font-size: 16px;
            color: #111;
            margin-top: 4px;
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 7px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
        }
        .sign-box {
            text-align: center;
            width: 200px;
        }
        .sign-space {
            height: 60px;
        }
        .no-print {
            margin-bottom: 20px;
        }
        .btn {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn">🖨️ Cetak Dokumen / Simpan PDF</button>
        <button onclick="window.close()" class="btn" style="background:#6b7280;">Tutup</button>
    </div>

    <div class="header">
        <h1>LAPORAN REKAPITULASI TRANSAKSI PARKIR</h1>
        <p>Sistem Manajemen Parkir Terpadu</p>
    </div>

    <div class="meta-info">
        <div>
            <strong>Periode Laporan:</strong> {{ date('d/m/Y', strtotime($dariTanggal)) }} s/d {{ date('d/m/Y', strtotime($sampaiTanggal)) }}
        </div>
        <div>
            <strong>Dicetak Pada:</strong> {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>

    <div class="summary-boxes">
        <div class="box">
            <span>Total Transaksi</span>
            <strong>{{ $totalTransaksi }} Kendaraan</strong>
        </div>
        <div class="box">
            <span>Transaksi Selesai</span>
            <strong>{{ $totalSelesai }} Selesai</strong>
        </div>
        <div class="box">
            <span>Masih Parkir</span>
            <strong>{{ $totalMasuk }} Unit</strong>
        </div>
        <div class="box">
            <span>Total Penerimaan</span>
            <strong style="color: #059669;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>No. Tiket</th>
                <th>Plat Nomor</th>
                <th>Jenis</th>
                <th>Area Parkir</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
                <th class="text-center">Durasi</th>
                <th class="text-right">Biaya (Rp)</th>
                <th>Petugas</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $index => $trx)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="font-family: monospace;">#TRX-{{ $trx->id_parkir }}</td>
                    <td style="font-weight: bold; font-family: monospace;">{{ $trx->kendaraan->plat_nomor ?? '-' }}</td>
                    <td style="text-transform: uppercase;">{{ $trx->tarif->jenis_kendaraan ?? '-' }}</td>
                    <td>{{ $trx->areaParkir->nama_area ?? '-' }}</td>
                    <td>{{ $trx->waktu_masuk->format('d/m/Y H:i') }}</td>
                    <td>{{ $trx->waktu_keluar ? $trx->waktu_keluar->format('d/m/Y H:i') : '-' }}</td>
                    <td class="text-center">{{ $trx->status === 'keluar' ? $trx->durasi_jam . ' Jam' : '-' }}</td>
                    <td class="text-right" style="font-weight: bold;">
                        {{ $trx->status === 'keluar' ? number_format($trx->biaya_total, 0, ',', '.') : '-' }}
                    </td>
                    <td>{{ $trx->user->nama_lengkap ?? '-' }}</td>
                    <td class="text-center" style="text-transform: uppercase;">{{ $trx->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada transaksi dalam periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background: #f9f9f9; font-weight: bold;">
                <td colspan="8" class="text-right">TOTAL PENDAPATAN DITERIMA:</td>
                <td class="text-right" style="color: #059669; font-size: 13px;">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-section">
        <div class="sign-box">
            <p style="margin: 0;">Disetujui Oleh,</p>
            <p style="margin: 4px 0 0; font-weight: bold;">Pemilik / Manajemen Parkir</p>
            <div class="sign-space"></div>
            <p style="margin: 0; font-weight: bold; border-top: 1px solid #333; padding-top: 4px;">
                ( ______________________ )
            </p>
        </div>
    </div>

</body>
</html>
