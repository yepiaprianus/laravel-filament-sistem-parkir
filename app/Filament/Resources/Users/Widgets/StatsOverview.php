<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class StatsOverview extends ChartWidget
{
    protected ?string $heading = 'User';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Total Users',
                    'data' => [User::count()],
                    'backgroundColor' => '#3B82F6',
                ],
            ],
            'labels' => ['Users'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
