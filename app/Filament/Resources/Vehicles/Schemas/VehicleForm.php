<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('plate_number')
                    ->label('Nomor Plat')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('H 1234 AB')
                    ->mutateDehydratedStateUsing(fn($state) => strtoupper($state))
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('nama', strtoupper($state));
                    }),
                // ->mask(fn(TextInput\Mask $mask) => $mask->pattern('H 9999 AA')->uppercase()),
                Select::make('vehicle_type_id')
                    ->label('Jenis Kendaraan')
                    ->relationship('vehicleType', 'name')
                    ->required()
                    ->preload(),
                TextInput::make('owner_name')
                    ->label('Nama Pemilik')
                    ->placeholder('Nama Pemilik (Opsional)'),
                Toggle::make('is_inside')
                    ->label('Sedang Parkir')
                    ->disabled() // Hanya sistem yang ubah status ini nanti
                    ->dehydrated(),
            ]);
    }
}
