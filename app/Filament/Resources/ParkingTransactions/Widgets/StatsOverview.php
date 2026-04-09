<?php

namespace App\Filament\Resources\ParkingTransactions\Widgets;

use App\Models\ParkingTransaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pendapatan Hari Ini', 'IDR ' . number_format(
                ParkingTransaction::whereDate('exit_time', today())->sum('total_price'),
                0,
                ',',
                '.'
            )),
            Stat::make('Kendaraan Masuk', ParkingTransaction::whereDate('entry_time', today())->count()),
        ];
    }
}
