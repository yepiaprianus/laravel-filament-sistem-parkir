<?php

namespace App\Filament\Resources\ParkingTransactions\Schemas;

use App\Models\ParkingArea;
use App\Models\ParkingFee;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Dom\Text;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ParkingTransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        $user = Auth::user();
        return $schema
            ->components([
                TextInput::make('ticket_number')
                    ->label('Ticket Number')
                    ->default('PRK-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3))))
                    ->disabled() // Tidak bisa diinput manual
                    ->dehydrated(),

                Select::make('parking_area_id')
                    ->label('Parking Area')
                    ->relationship('parkingArea', 'name')
                    ->live()
                    ->helperText(function ($get) {
                        $areaId = $get('parking_area_id');
                        if (! $areaId) return null;
                        $area = ParkingArea::find($areaId);
                        if (! $area) return null;
                        $sisa = $area->capacity - $area->current_occupancy;
                        return "Kapasitas: {$area->capacity} | Terisi: {$area->current_occupancy} | Sisa: {$sisa}";
                    })
                    ->required(),

                Select::make('vehicle_type_filter')
                    ->label('Vehicle Type (Filter)')
                    ->dehydrated(false)
                    ->options(function () {
                        return VehicleType::distinct('id')->pluck('name', 'id');
                    })->live()
                    ->afterStateHydrated(function (Set $set, $record) {
                        if ($record && $record->vehicle) {
                            $set('vehicle_type_filter', $record->vehicle->vehicle_type_id);
                            $fee = $record->parkingFee;
                            if ($fee) {
                                $set('hourly_price', $fee->hourly_price);
                            }
                        }
                    })
                    ->afterStateUpdated(function ($record, Set $set, $state) {
                        $set('vehicle_id', null);
                        if ($record) {
                            $set('vehicle_type_filter', $record->vehicle->vehicle_type_id);
                        };

                        $parkingFee = ParkingFee::where('vehicle_type_id', $state)->first();

                        if ($parkingFee) {
                            $set('hourly_price', $parkingFee->hourly_price);
                            $set('parking_fee_id', $parkingFee->id);
                        } else {
                            $set('hourly_price', null);
                            $set('parking_fee_id', null);
                        }
                        // $set('hourly_price', null);
                    })
                    ->required(),

                Hidden::make('parking_fee_id')
                    ->dehydrated(),

                Select::make('vehicle_id')
                    ->label('Vehicle (Plate Number)')
                    ->relationship('vehicle', 'plate_number', fn($query, Get $get) =>
                    $query->where(
                        fn($q) => $q->where('is_inside', false)
                            ->where('vehicle_type_id', $get('vehicle_type_filter'))
                    )
                        ->orWhere('id', $get('vehicle_id')))
                    ->searchable()
                    ->disabled(fn(Get $get) => !$get('vehicle_type_filter'))
                    ->preload()
                    ->createOptionForm(
                        function (Get $get) {
                            $selectedTypeId = $get('vehicle_type_filter');
                            return [
                                TextInput::make('plate_number')
                                    ->required()
                                    ->dehydrateStateUsing(fn($state) => strtoupper($state))
                                    ->unique('vehicles', 'plate_number'),
                                Hidden::make('vehicle_type_id')
                                    // ->hidden()
                                    ->default($selectedTypeId)
                                    ->dehydrated(),
                                // ->dehydrateStateUsing(fn($state, Get $get, Set $set) => $set('vehicle_type_id', $get('vehicle.vehicle_type_id'))),
                                TextInput::make('owner_name')
                                    ->label('Nama Pemilik')
                                    ->placeholder('Nama Pemilik (Opsional)'),

                                TextEntry::make('info')
                                    // ->dehydrateStateUsing(fn($state, Get $get) => "Nomor Plat: {$get('plate_number')} | Jenis Kendaraan: {$get('vehicle_type_id')}")
                                    ->label('Kendaraan ini akan otomatis terdaftar sebagai jenis yang Anda pilih sebelumnya.')
                                    ->color('warning')
                                    ->icon('heroicon-o-exclamation-triangle')
                                    ->visible(fn(Get $get) => $get('plate_number') || $get('vehicle_type_id'))
                                    ->dehydrated(false),
                            ];
                        }
                    )
                    ->required(),
                DateTimePicker::make('entry_time')
                    ->required()
                    ->label('Entry Time')
                    ->readOnly() // Waktu masuk diatur otomatis saat transaksi dibuat
                    ->dehydrated()
                    ->default(Carbon::now()->format('Y-m-d H:i:s')),

                TextInput::make('hourly_price')
                    ->label('Parking Fee')
                    ->numeric()
                    ->prefix('IDR')
                    ->dehydrated(false)
                    ->readOnly()
                    ->visible(fn(Get $get) => $get('vehicle_type_filter') !== null),
                // ->relationship('parkingFee', 'hourly_price', fn($query, Get $get) => $query->select('hourly_price')
                //     ->whereColumn('vehicle_type_id', $get('vehicle.vehicle_type_id'))),
                // ->visible(fn(Get $get) => $get('vehicle_id') !== null)
                // ->helperText(function (Get $get) {
                //     $vehicleId = $get('vehicle_type_filter');
                //     if (! $vehicleId) return null;
                //     $vehicle = Vehicle::find($vehicleId);
                //     if (! $vehicle) return null;
                //     return "Tarif untuk jenis kendaraan ini: IDR {$vehicle->vehicleType->hourly_price} per jam";
                // }),
                // DateTimePicker::make('exit_time'),
                // TextInput::make('total_price')
                //     ->required()
                //     ->numeric()
                //     ->default(0.0)
                //     ->prefix('$'),
                // Select::make('status')
                //     ->options(['parked' => 'Parked', 'completed' => 'Completed', 'lost_ticket' => 'Lost ticket'])
                //     ->default('parked')
                //     ->required(),
                // Select::make('entry_officer_id')
                //     ->label('Petugas Input')
                //     ->relationship('entryOfficer', 'name', fn($query) => $query->where('role', 'officer'))
                //     ->required()
                //     ->preload()
                //     ->visible(fn() => $user->role === 'admin')
                //     ->searchable(),
                // Select::make('exit_officer_id')
                //     ->label('Petugas Keluar')
                //     ->relationship('exitOfficer', 'name', fn($query) => $query->where('role', 'officer'))
                //     ->searchable(),

                Hidden::make('entry_officer_id')
                    ->default($user->id),
                // ->visible(fn() => $user->role !== 'admin'),

                // DateTimePicker::make('exit_time')
                //     ->label('Waktu Keluar')
                //     ->live()
                //     ->afterStateUpdated(function ($state, Set $set, Get $get) {
                //         $entryTime = $get('entry_time');
                //         $vehicleTypeId = $get('vehicle_type_filter');

                //         if ($state && $entryTime && $vehicleTypeId) {
                //             $vehiclePrice = ParkingFee::where('vehicle_type_id', $vehicleTypeId)->first();
                //             $tarif = $vehiclePrice->hourly_price;

                //             $start = Carbon::parse($entryTime);
                //             $end = Carbon::parse($state);

                //             $hours = ceil($start->diffInMinutes($end) / 60);
                //             if ($hours < 1) $hours = 1;
                //             $total = ($hours * $tarif);
                //             $set('total_price', $total);
                //         }
                //     })->visible(fn($record) => $record !== null && $record->status === 'parked'), // Hanya muncul saat status masih parkir

                // TextInput::make('total_price')
                //     ->numeric()
                //     ->prefix('IDR')
                //     ->dehydrated()
                //     ->visible(fn($record) => $record !== null && $record->status === 'parked')
                //     ->readOnly(),

                // Hidden::make('exit_officer_id')
                //     ->default($user->id)
                //     ->visible(fn() => $user->role !== 'admin'),
            ])
            ->columns(2);
    }
}
