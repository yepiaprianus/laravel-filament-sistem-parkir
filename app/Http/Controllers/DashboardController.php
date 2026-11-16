<?php

namespace App\Http\Controllers;

use App\Models\AreaParkir;
use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use App\Models\Tarif;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            'owner' => redirect()->route('owner.dashboard'),
            default => abort(403),
        };
    }

    public function adminDashboard()
    {
        $totalUser = User::count();
        $totalArea = AreaParkir::count();
        $totalKendaraan = Kendaraan::count();
        $transaksiAktif = Transaksi::where('status', 'masuk')->count();
        $totalPendapatan = Transaksi::where('status', 'keluar')->sum('biaya_total');

        $areas = AreaParkir::all();
        $recentLogs = LogAktivitas::with('user')->latest('waktu_aktivitas')->limit(8)->get();
        $recentTransactions = Transaksi::with(['kendaraan', 'areaParkir', 'tarif', 'user'])
            ->latest('waktu_masuk')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUser',
            'totalArea',
            'totalKendaraan',
            'transaksiAktif',
            'totalPendapatan',
            'areas',
            'recentLogs',
            'recentTransactions'
        ));
    }

    public function petugasDashboard()
    {
        $petugasId = Auth::id();
        $transaksiAktif = Transaksi::where('status', 'masuk')->count();
        $masukHariIni = Transaksi::whereDate('waktu_masuk', today())->count();
        $keluarHariIni = Transaksi::where('status', 'keluar')->whereDate('waktu_keluar', today())->count();
        $pendapatanHariIni = Transaksi::where('status', 'keluar')->whereDate('waktu_keluar', today())->sum('biaya_total');

        $areas = AreaParkir::all();
        $recentTransactions = Transaksi::with(['kendaraan', 'areaParkir', 'tarif'])
            ->where('status', 'masuk')
            ->latest('waktu_masuk')
            ->limit(10)
            ->get();

        return view('petugas.dashboard', compact(
            'transaksiAktif',
            'masukHariIni',
            'keluarHariIni',
            'pendapatanHariIni',
            'areas',
            'recentTransactions'
        ));
    }

    public function ownerDashboard()
    {
        $totalPendapatan = Transaksi::where('status', 'keluar')->sum('biaya_total');
        $pendapatanBulanIni = Transaksi::where('status', 'keluar')
            ->whereMonth('waktu_keluar', now()->month)
            ->whereYear('waktu_keluar', now()->year)
            ->sum('biaya_total');

        $totalTransaksi = Transaksi::count();
        $transaksiSelesai = Transaksi::where('status', 'keluar')->count();
        $transaksiAktif = Transaksi::where('status', 'masuk')->count();

        $areas = AreaParkir::all();
        $recentTransactions = Transaksi::with(['kendaraan', 'areaParkir', 'tarif', 'user'])
            ->latest('waktu_masuk')
            ->limit(10)
            ->get();

        return view('owner.dashboard', compact(
            'totalPendapatan',
            'pendapatanBulanIni',
            'totalTransaksi',
            'transaksiSelesai',
            'transaksiAktif',
            'areas',
            'recentTransactions'
        ));
    }
}
