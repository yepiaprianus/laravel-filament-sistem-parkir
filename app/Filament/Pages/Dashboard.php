<?php

namespace App\Filament\Pages;


use App\Filament\Widgets\ParkingStatsOverview;
use App\Filament\Widgets\RecentTransactionsTable;
use App\Filament\Widgets\TransactionChart;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Dashboard extends Page
{
    protected string $view = 'filament.pages.dashboard';
    protected static ?string $pollingInterval = '10s';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::HomeModern;
    // protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'Dashboard';
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-home';
    }

    // public static function getNavigationGroup(): ?string
    // {
    //     return 'Dashboard';
    // }
    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public function getHeaderWidgets(): array
    {
        return [
            // StatsOverview::class,
            // WidgetsStatsOverview::class,
            ParkingStatsOverview::class,
            TransactionChart::class,
            RecentTransactionsTable::class,
        ];
    }
}
