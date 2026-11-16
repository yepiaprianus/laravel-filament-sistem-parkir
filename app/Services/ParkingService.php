<?php

namespace App\Services;

use App\Models\AreaParkir;
use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use App\Models\Tarif;
use App\Models\Transaksi;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class ParkingService
{
    /**
     * Proses transaksi kendaraan masuk
     */
    public function prosesMasuk(array $data, int $idUser): Transaksi
    {
        $platNomor = strtoupper(trim($data['plat_nomor']));
        $jenisKendaraan = strtolower($data['jenis_kendaraan']);
        $idArea = (int) $data['id_area'];

        // 1. Cek apakah area parkir ada dan belum penuh
        $area = AreaParkir::findOrFail($idArea);
        if ($area->isPenuh()) {
            throw new Exception("Area parkir '{$area->nama_area}' sudah penuh!");
        }

        // 2. Cek apakah tarif untuk jenis kendaraan ada
        $tarif = Tarif::where('jenis_kendaraan', $jenisKendaraan)->first();
        if (!$tarif) {
            throw new Exception("Tarif parkir untuk jenis kendaraan '{$jenisKendaraan}' belum ditentukan.");
        }

        // 3. Cek apakah kendaraan dengan plat ini masih tercatat aktif (status: masuk)
        $transaksiAktif = Transaksi::whereHas('kendaraan', function ($query) use ($platNomor) {
            $query->where('plat_nomor', $platNomor);
        })->where('status', 'masuk')->first();

        if ($transaksiAktif) {
            throw new Exception("Kendaraan dengan plat nomor {$platNomor} sedang berada di dalam area parkir!");
        }

        // 4. Buat atau ambil record kendaraan
        $kendaraan = Kendaraan::firstOrCreate(
            ['plat_nomor' => $platNomor],
            [
                'jenis_kendaraan' => $jenisKendaraan,
                'warna' => $data['warna'] ?? null,
                'pemilik' => $data['pemilik'] ?? null,
                'id_user' => $idUser,
            ]
        );

        // Jika kendaraan sudah ada tapi jenis diubah, perbarui
        if ($kendaraan->jenis_kendaraan !== $jenisKendaraan) {
            $kendaraan->update(['jenis_kendaraan' => $jenisKendaraan]);
        }

        return DB::transaction(function () use ($kendaraan, $area, $tarif, $idUser) {
            $transaksi = Transaksi::create([
                'id_kendaraan' => $kendaraan->id_kendaraan,
                'waktu_masuk' => now(),
                'waktu_keluar' => null,
                'id_tarif' => $tarif->id_tarif,
                'durasi_jam' => 0,
                'biaya_total' => 0,
                'status' => 'masuk',
                'id_user' => $idUser,
                'id_area' => $area->id_area,
            ]);

            $area->increment('terisi');

            LogAktivitas::catat(
                $idUser,
                "Kendaraan {$kendaraan->plat_nomor} ({$kendaraan->jenis_kendaraan}) masuk ke {$area->nama_area}"
            );

            return $transaksi->load(['kendaraan', 'tarif', 'areaParkir', 'user']);
        });
    }

    /**
     * Proses transaksi kendaraan keluar & hitung biaya
     */
    public function prosesKeluar(int $idParkir, int $idUser): Transaksi
    {
        $transaksi = Transaksi::with(['kendaraan', 'tarif', 'areaParkir'])->findOrFail($idParkir);

        if ($transaksi->status !== 'masuk') {
            throw new Exception("Transaksi ini sudah selesai (kendaraan sudah keluar).");
        }

        $waktuMasuk = Carbon::parse($transaksi->waktu_masuk);
        $waktuKeluar = now();

        $diffMinutes = $waktuMasuk->diffInMinutes($waktuKeluar);
        $durasiJam = max(1, (int) ceil($diffMinutes / 60));
        $tarifPerJam = (float) $transaksi->tarif->tarif_per_jam;
        $biayaTotal = $durasiJam * $tarifPerJam;

        return DB::transaction(function () use ($transaksi, $waktuKeluar, $durasiJam, $biayaTotal, $idUser) {
            $transaksi->update([
                'waktu_keluar' => $waktuKeluar,
                'durasi_jam' => $durasiJam,
                'biaya_total' => $biayaTotal,
                'status' => 'keluar',
            ]);

            if ($transaksi->areaParkir && $transaksi->areaParkir->terisi > 0) {
                $transaksi->areaParkir->decrement('terisi');
            }

            LogAktivitas::catat(
                $idUser,
                "Kendaraan {$transaksi->kendaraan->plat_nomor} keluar. Durasi: {$durasiJam} jam, Total: Rp " . number_format($biayaTotal, 0, ',', '.')
            );

            return $transaksi;
        });
    }
}
