<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\AreaParkir;
use App\Models\Tarif;
use App\Models\Transaksi;
use App\Services\ParkingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $currentStatus = $request->input('status', 'masuk');

        $query = Transaksi::with(['kendaraan', 'areaParkir', 'tarif', 'user']);

        if ($currentStatus === 'masuk') {
            $query->where('status', 'masuk');
        } elseif ($currentStatus === 'keluar') {
            $query->where('status', 'keluar');
        } // if 'all', do not filter status

        if ($request->filled('search')) {
            $search = strtoupper(trim($request->search));
            $query->whereHas('kendaraan', function ($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%");
            });
        }

        if ($request->filled('id_area')) {
            $query->where('id_area', $request->id_area);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_masuk', $request->tanggal);
        }

        $transaksis = $query->latest('waktu_masuk')->paginate(10)->withQueryString();
        $areas = AreaParkir::all();

        // Metrics for tabs
        $countAktif = Transaksi::where('status', 'masuk')->count();
        $countKeluarHariIni = Transaksi::where('status', 'keluar')->whereDate('waktu_keluar', today())->count();
        $totalPendapatanHariIni = Transaksi::where('status', 'keluar')->whereDate('waktu_keluar', today())->sum('biaya_total');

        return view('petugas.transaksi.index', compact(
            'transaksis',
            'areas',
            'currentStatus',
            'countAktif',
            'countKeluarHariIni',
            'totalPendapatanHariIni'
        ));
    }

    public function masukForm()
    {
        $areas = AreaParkir::all();
        $tarifs = Tarif::all();
        return view('petugas.transaksi.masuk', compact('areas', 'tarifs'));
    }

    public function storeMasuk(Request $request, ParkingService $parkingService)
    {
        $validated = $request->validate([
            'plat_nomor' => ['required', 'string', 'max:15'],
            'jenis_kendaraan' => ['required', 'in:motor,mobil,lainnya'],
            'id_area' => ['required', 'exists:tb_area_parkir,id_area'],
            'warna' => ['nullable', 'string', 'max:20'],
            'pemilik' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $transaksi = $parkingService->prosesMasuk($validated, Auth::id());

            return redirect()->route('petugas.transaksi.index', ['status' => 'masuk'])
                ->with('success', "Kendaraan {$transaksi->kendaraan->plat_nomor} berhasil masuk di {$transaksi->areaParkir->nama_area}.")
                ->with('cetak_masuk_id', $transaksi->id_parkir);
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function checkoutPreview(Transaksi $transaksi)
    {
        if ($transaksi->status !== 'masuk') {
            return redirect()->route('petugas.transaksi.index')->with('error', 'Transaksi sudah diselesaikan.');
        }

        $estimasi = $transaksi->hitungEstimasiBiaya(now());

        return view('petugas.transaksi.keluar-preview', compact('transaksi', 'estimasi'));
    }

    public function storeKeluar(Transaksi $transaksi, ParkingService $parkingService)
    {
        try {
            $result = $parkingService->prosesKeluar($transaksi->id_parkir, Auth::id());

            return redirect()->route('petugas.transaksi.index', ['status' => 'keluar'])
                ->with('success', "Transaksi kendaraan {$result->kendaraan->plat_nomor} selesai. Total Biaya: Rp " . number_format($result->biaya_total, 0, ',', '.'))
                ->with('cetak_keluar_id', $result->id_parkir);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function strukMasuk(Transaksi $transaksi)
    {
        return view('struk.tiket-masuk', compact('transaksi'));
    }

    public function strukKeluar(Transaksi $transaksi)
    {
        if ($transaksi->status !== 'keluar') {
            return redirect()->route('petugas.transaksi.index')->with('error', 'Struk keluar hanya dapat dicetak setelah checkout.');
        }

        return view('struk.struk-keluar', compact('transaksi'));
    }
}
