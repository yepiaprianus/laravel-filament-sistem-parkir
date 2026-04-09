<?php

namespace App\Filament\Resources\ParkingAreas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ParkingAreaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Parking Area Name')
                    ->unique(ignoreRecord: true),
                TextInput::make('capacity')
                    ->required()
                    ->numeric()
                    ->label('Capacity'),
                TextInput::make('current_occupancy')
                    ->readOnly()
                    ->numeric()
                    ->default(0)
                    ->label('Current Occupancy'),
            ]);
    }
}
