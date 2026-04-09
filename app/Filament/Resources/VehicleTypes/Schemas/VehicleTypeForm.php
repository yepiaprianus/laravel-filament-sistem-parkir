<?php

namespace App\Filament\Resources\VehicleTypes\Schemas;

use Dom\Text;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VehicleTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->unique(ignoreRecord: true)
                    ->mutateDehydratedStateUsing(fn($state) => strtoupper($state))
                    ->required(),
                TextInput::make('description')
                    ->label('Description')
                    ->nullable(),
            ]);
    }
}
