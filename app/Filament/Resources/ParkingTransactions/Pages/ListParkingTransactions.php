<?php

namespace App\Filament\Resources\ParkingTransactions\Pages;

use App\Filament\Resources\ParkingTransactions\ParkingTransactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListParkingTransactions extends ListRecords
{
    protected static string $resource = ParkingTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
