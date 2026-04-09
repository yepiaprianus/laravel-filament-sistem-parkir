<?php

namespace App\Filament\Resources\ParkingTransactions\Pages;

use App\Filament\Resources\ParkingTransactions\ParkingTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditParkingTransaction extends EditRecord
{
    protected static string $resource = ParkingTransactionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
