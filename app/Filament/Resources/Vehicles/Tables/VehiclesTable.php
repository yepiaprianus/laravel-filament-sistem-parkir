<?php

namespace App\Filament\Resources\Vehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        $user = Auth::user();

        return $table
            ->columns([
                TextColumn::make('plate_number')
                    ->label('Plate Nomor')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('vehicleType.name')
                    ->label('Jenis Kendaraan')
                    ->alignCenter()
                    ->badge(),
                TextColumn::make('owner_name')
                    ->label('Nama Pemilik')
                    ->alignCenter()
                    ->searchable(),
                IconColumn::make('is_inside')
                    ->label('Status Parkir')
                    ->boolean()
                    ->alignCenter()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->color(fn(bool $state): string => $state ? 'success' : 'gray'),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // --- IGNORE ---
                SelectFilter::make('is_inside')
                    ->label('Is Inside')
                    ->options([
                        1 => 'Yes',
                        0 => 'No',
                    ]),
                SelectFilter::make('vehicle_type_id')
                    ->label('Jenis Kendaraan')
                    ->relationship('vehicleType', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->visible(fn($record) => $user->role === 'admin'), // Hanya admin yang bisa hapus
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
