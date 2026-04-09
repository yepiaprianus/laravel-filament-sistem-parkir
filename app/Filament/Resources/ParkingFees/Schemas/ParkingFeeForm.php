<?php

namespace App\Filament\Resources\ParkingFees\Schemas;

use App\Models\ParkingFee;
use App\Models\VehicleType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ParkingFeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vehicle_type_id')
                    ->label("Vehicle Type")
                    ->relationship(
                        name: 'vehicleType',
                        titleAttribute: 'name',
                        modifyQueryUsing: function ($query, $record) {
                            $existingFeeIds = ParkingFee::pluck('vehicle_type_id')->toArray();

                            if ($record) {
                                $existingFeeIds = array_diff($existingFeeIds, [$record->vehicle_type_id]);
                            }

                            return $query->whereNotIn('id', $existingFeeIds);
                        }
                    )
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->label('Vehicle Type Name')
                            ->unique('vehicle_types', 'name')
                            ->mutateDehydratedStateUsing(fn($state) => strtoupper($state))
                            ->afterStateUpdated(fn($state, callable $set) => $set('name', strtoupper($state))),
                        TextInput::make('description')
                            ->label('Description')
                            ->nullable(),
                    ])->unique(ignoreRecord: true),
                TextInput::make('hourly_price')
                    ->required()
                    ->numeric(),
                // ->prefix('$'),
            ]);
    }
}
