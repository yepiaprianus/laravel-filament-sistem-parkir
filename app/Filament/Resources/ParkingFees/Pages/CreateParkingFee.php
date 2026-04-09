<?php

namespace App\Filament\Resources\ParkingFees\Pages;

use App\Filament\Resources\ParkingFees\ParkingFeeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateParkingFee extends CreateRecord
{
    protected static string $resource = ParkingFeeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Parking Fee Created';
    }

    protected function getCreatedNotificationMessage(): ?string
    {
        return 'The parking fee has been successfully created.';
    }
}
