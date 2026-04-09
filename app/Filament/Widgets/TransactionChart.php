<?php

namespace App\Filament\Widgets;

use App\Models\ParkingTransaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TransactionChart extends ChartWidget
{
    protected ?string $heading = 'Transaksi';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Loop untuk 12 bulan dalam setahun ini
        for ($m = 1; $m <= 12; $m++) {
            $month = Carbon::create(now()->year, $m, 1);

            $labels[] = $month->translatedFormat('F'); // Contoh: Januari, Februari...

            $data[] = ParkingTransaction::whereYear('exit_time', now()->year)
                ->whereMonth('exit_time', $m)
                ->where('status', 'completed')
                ->sum('total_price');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Pendapatan (IDR)',
                    'data' => $data,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, .1)',
                    'fill' => 'start',
                    'tension' => 0.4, // Membuat garis sedikit melengkung agar lebih halus
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
