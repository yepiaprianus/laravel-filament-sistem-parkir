<?php

namespace App\Filament\Widgets;

use App\Models\ParkingTransaction;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ParkingStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        $stats = [];

        if ($user->role === 'admin') {
            $stats[] = Stat::make('Total User (Lainnya)', User::where('id', '!=', $user->id)->count())
                ->description('Semua user selain Anda')
                ->icon('heroicon-m-users');

            $stats[] = Stat::make('Total Officer', User::where('role', 'petugas')->count())
                ->icon('heroicon-m-user-group')
                ->color('info');

            $stats[] = Stat::make('Total Owner', User::where('role', 'owner')->count())
                ->icon('heroicon-m-briefcase')
                ->color('info');
        }

        // --- KENDARAAN (Muncul untuk semua role/disesuaikan) ---
        $stats[] = Stat::make('Total Kendaraan', Vehicle::count())
            ->description('Terdaftar di sistem')
            ->icon('heroicon-m-truck');

        $stats[] = Stat::make('Sedang Parkir', Vehicle::where('is_inside', true)->count())
            ->description('Kendaraan di dalam area')
            ->color('warning')
            ->icon('heroicon-m-arrow-right-on-rectangle');

        $stats[] = Stat::make('Tidak Parkir', Vehicle::where('is_inside', false)->count())
            ->description('Kendaraan di luar area')
            ->color('success')
            ->icon('heroicon-m-check-circle');

        // --- TRANSAKSI & PENDAPATAN ---
        $stats[] = Stat::make('Total Pendapatan', 'IDR ' . number_format(ParkingTransaction::sum('total_price'), 0, ',', '.'))
            ->icon('heroicon-m-banknotes')
            ->color('success');

        $stats[] = Stat::make('Pendapatan Bulan Ini', 'IDR ' . number_format(
            ParkingTransaction::whereMonth('exit_time', now()->month)
                ->whereYear('exit_time', now()->year)
                ->sum('total_price'),
            0,
            ',',
            '.'
        ))
            ->icon('heroicon-m-calendar');

        $stats[] = Stat::make('Pendapatan Hari Ini', 'IDR ' . number_format(
            ParkingTransaction::whereDate('exit_time', today())->sum('total_price'),
            0,
            ',',
            '.'
        ))
            ->icon('heroicon-m-clock')
            ->color('primary');

        return $stats;
    }
}
