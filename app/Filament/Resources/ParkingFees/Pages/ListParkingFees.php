<?php

namespace App\Filament\Resources\ParkingFees\Pages;

use App\Filament\Resources\ParkingFees\ParkingFeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListParkingFees extends ListRecords
{
    protected static string $resource = ParkingFeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
