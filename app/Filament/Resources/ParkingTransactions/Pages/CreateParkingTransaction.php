<?php

namespace App\Filament\Resources\ParkingTransactions\Pages;

use App\Filament\Resources\ParkingTransactions\ParkingTransactionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateParkingTransaction extends CreateRecord
{
    protected static string $resource = ParkingTransactionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // public static function canAccess(): bool
    // {
    //     return FacadesAuth::user()->role === 'officer';
    // }

    public static function canAccess(array $parameters = []): bool
    {
        return Auth::user()->role === 'officer';
    }
}
