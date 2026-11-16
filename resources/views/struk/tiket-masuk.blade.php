<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Masuk #TRX-{{ $transaksi->id_parkir }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .ticket {
            background: white;
            width: 300px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px dashed #ccc;
            text-align: center;
        }
        .header {
            border-bottom: 1px dashed #444;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        .subtitle {
            font-size: 11px;
            color: #555;
            margin-top: 4px;
        }
        .plate-box {
            border: 2px solid #000;
            padding: 8px 4px;
            margin: 12px 0;
            font-size: 22px;
            font-weight: 900;
            letter-spacing: 2px;
        }
        .info-table {
            width: 100%;
            font-size: 12px;
            text-align: left;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 3px 0;
        }
        .footer {
            border-top: 1px dashed #444;
            padding-top: 10px;
            font-size: 11px;
            color: #555;
        }
        .barcode {
            margin: 10px 0;
            font-size: 20px;
            letter-spacing: 5px;
            font-weight: bold;
        }
        .btn-group {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .btn {
            padding: 8px 16px;
            font-family: sans-serif;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }
        .btn-print { background: #4f46e5; color: white; }
        .btn-close { background: #e5e7eb; color: #374151; }

        @media print {
            body { background: white; padding: 0; }
            .ticket { box-shadow: none; border: none; width: 100%; max-width: 280px; padding: 5px; }
            .btn-group { display: none; }
        }
    </style>
</head>
<body>

    <div class="ticket">
        <div class="header">
            <h1 class="title">TIKET PARKIR MASUK</h1>
            <p class="subtitle">SISTEM MANAJEMEN PARKIR</p>
        </div>

        <div class="barcode">*{{ sprintf('%06d', $transaksi->id_parkir) }}*</div>
        <div class="plate-box">{{ $transaksi->kendaraan->plat_nomor }}</div>

        <table class="info-table">
            <tr>
                <td>No. Tiket</td>
                <td>: #TRX-{{ $transaksi->id_parkir }}</td>
            </tr>
            <tr>
                <td>Jenis Kendaraan</td>
                <td>: {{ strtoupper($transaksi->tarif->jenis_kendaraan) }}</td>
            </tr>
            <tr>
                <td>Area Parkir</td>
                <td>: {{ $transaksi->areaParkir->nama_area }}</td>
            </tr>
            <tr>
                <td>Waktu Masuk</td>
                <td>: {{ $transaksi->waktu_masuk->format('d/m/Y H:i:s') }}</td>
            </tr>
            <tr>
                <td>Tarif / Jam</td>
                <td>: Rp {{ number_format($transaksi->tarif->tarif_per_jam, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Petugas</td>
                <td>: {{ $transaksi->user->nama_lengkap ?? '-' }}</td>
            </tr>
        </table>

        <div class="footer">
            <p style="margin: 0;">Simpan tiket ini dengan baik.</p>
            <p style="margin: 4px 0 0 0;">Tiket hilang dikenakan denda sesuai ketentuan.</p>
        </div>
    </div>

    <div class="btn-group">
        <button onclick="window.print()" class="btn btn-print">🖨️ Cetak Tiket</button>
        <button onclick="window.close()" class="btn btn-close">Tutup</button>
    </div>

</body>
</html>
