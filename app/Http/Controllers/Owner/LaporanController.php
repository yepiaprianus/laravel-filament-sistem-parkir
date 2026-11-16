<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\AreaParkir;
use App\Models\Tarif;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->toDateString());
        $sampaiTanggal = $request->input('sampai_tanggal', now()->toDateString());

        $query = Transaksi::with(['kendaraan', 'areaParkir', 'tarif', 'user'])
            ->whereDate('waktu_masuk', '>=', $dariTanggal)
            ->whereDate('waktu_masuk', '<=', $sampaiTanggal);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_kendaraan')) {
            $query->whereHas('tarif', function ($q) use ($request) {
                $q->where('jenis_kendaraan', $request->jenis_kendaraan);
            });
        }

        if ($request->filled('id_area')) {
            $query->where('id_area', $request->id_area);
        }

        // Summary metrics
        $cloneQuery = clone $query;
        $totalPendapatan = (clone $query)->where('status', 'keluar')->sum('biaya_total');
        $totalTransaksi = (clone $query)->count();
        $totalSelesai = (clone $query)->where('status', 'keluar')->count();
        $totalMasuk = (clone $query)->where('status', 'masuk')->count();

        $transaksis = $query->latest('waktu_masuk')->paginate(15)->withQueryString();

        $areas = AreaParkir::all();
        $tarifs = Tarif::all();

        return view('owner.laporan.index', compact(
            'transaksis',
            'dariTanggal',
            'sampaiTanggal',
            'totalPendapatan',
            'totalTransaksi',
            'totalSelesai',
            'totalMasuk',
            'areas',
            'tarifs'
        ));
    }

    public function cetak(Request $request)
    {
        $dariTanggal = $request->input('dari_tanggal', now()->startOfMonth()->toDateString());
        $sampaiTanggal = $request->input('sampai_tanggal', now()->toDateString());

        $query = Transaksi::with(['kendaraan', 'areaParkir', 'tarif', 'user'])
            ->whereDate('waktu_masuk', '>=', $dariTanggal)
            ->whereDate('waktu_masuk', '<=', $sampaiTanggal);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_kendaraan')) {
            $query->whereHas('tarif', function ($q) use ($request) {
                $q->where('jenis_kendaraan', $request->jenis_kendaraan);
            });
        }

        if ($request->filled('id_area')) {
            $query->where('id_area', $request->id_area);
        }

        $totalPendapatan = (clone $query)->where('status', 'keluar')->sum('biaya_total');
        $totalTransaksi = (clone $query)->count();
        $totalSelesai = (clone $query)->where('status', 'keluar')->count();
        $totalMasuk = (clone $query)->where('status', 'masuk')->count();

        $transaksis = $query->latest('waktu_masuk')->get();

        return view('owner.laporan.cetak', compact(
            'transaksis',
            'dariTanggal',
            'sampaiTanggal',
            'totalPendapatan',
            'totalTransaksi',
            'totalSelesai',
            'totalMasuk'
        ));
    }
}
