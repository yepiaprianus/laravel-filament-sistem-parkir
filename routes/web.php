<?php

use App\Http\Controllers\Admin\AreaParkirController;
use App\Http\Controllers\Admin\KendaraanController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\Admin\TarifController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Owner\LaporanController;
use App\Http\Controllers\Petugas\TransaksiController;
use Illuminate\Support\Facades\Route;

// Redirect Home
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ================= ADMIN =================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('areas', AreaParkirController::class)->except(['show']);
        Route::resource('tarifs', TarifController::class)->except(['show']);
        Route::resource('kendaraans', KendaraanController::class)->except(['show']);
        Route::get('/logs', [LogAktivitasController::class, 'index'])->name('logs.index');
    });

    // ================= PETUGAS =================
    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'petugasDashboard'])->name('dashboard');
        Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/masuk', [TransaksiController::class, 'masukForm'])->name('transaksi.masuk');
        Route::post('/transaksi/masuk', [TransaksiController::class, 'storeMasuk'])->name('transaksi.masuk.store');
        Route::get('/transaksi/{transaksi}/checkout', [TransaksiController::class, 'checkoutPreview'])->name('transaksi.checkout');
        Route::post('/transaksi/{transaksi}/checkout', [TransaksiController::class, 'storeKeluar'])->name('transaksi.checkout.store');
        Route::get('/transaksi/{transaksi}/struk-masuk', [TransaksiController::class, 'strukMasuk'])->name('transaksi.struk-masuk');
        Route::get('/transaksi/{transaksi}/struk-keluar', [TransaksiController::class, 'strukKeluar'])->name('transaksi.struk-keluar');
    });

    // ================= OWNER =================
    Route::middleware('role:owner')->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'ownerDashboard'])->name('dashboard');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    });
});
