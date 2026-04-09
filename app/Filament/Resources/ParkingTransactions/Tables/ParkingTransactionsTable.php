<?php

namespace App\Filament\Resources\ParkingTransactions\Tables;

use App\Models\ActivityLog;
use Auth;
use Carbon\Carbon;
use Dom\Text;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class ParkingTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_number')
                    ->label('Ticket Number')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('vehicle.plate_number')
                    ->label('Plate Number')
                    ->searchable()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('vehicle.vehicleType.name')
                    ->label('Vehicle Type')
                    ->color('info')
                    ->alignCenter()
                    ->badge(),
                TextColumn::make('parkingArea.name')
                    ->label('Parking Area')
                    // ->description(fn($record): string => "Capacity: {$record->parkingArea->capacity}, Occupied: {$record->parkingArea->current_occupancy}")
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('entry_time')
                    ->label('Entry Time')
                    ->dateTime('d M Y H:i:s')
                    ->alignCenter()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('exit_time')
                    ->label('Exit Time')
                    ->dateTime('d M Y H:i:s')
                    ->alignCenter()
                    ->color('success')
                    ->placeholder('-')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('d M Y H:i:s') : '-')
                    ->sortable(),
                TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('IDR', true)
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('status')
                    // ->formatState(fn(string $state): string => match ($state) {
                    //     'parked' => 'Masih Parkir',
                    //     'completed' => 'Selesai',
                    //     'lost_ticket' => 'Struk Hilang',
                    //     default => $state,
                    // })
                    ->label('Status')
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        'parked' => 'warning',
                        'completed' => 'success',
                        'lost_ticket' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state) => ucfirst($state))
                    ->badge(),
                TextColumn::make('entryOfficer.name')
                    ->label('Entry Officer')
                    ->formatStateUsing(fn($state) => $state ?? '-')
                    ->alignCenter()
                    // ->description(fn($record): string => "Role: " . ($record->entryOfficer->role ?? '-'))
                    ->searchable(),
                TextColumn::make('exitOfficer.name')
                    ->label('Exit Officer')
                    ->alignCenter()
                    ->placeholder('-')
                    ->getStateUsing(fn($record) => $record->exitOfficer?->name)
                    // ->description(fn($record): string => "Role: " . ($record->exitOfficer->role ?? '-'))
                    ->searchable(),
                // TextColumn::make('created_at')
                //     ->dateTime('d M Y H:i')
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime('d M Y H:i')
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'parked' => 'Masih Parkir',
                        'completed' => 'Selesai',
                    ]),
            ])
            ->recordActions([
                Action::make('checkout')
                    // ->label(fn($record) => "Checkout (" . $record->status . " - " . FacadesAuth::user()->role . ")")
                    ->label('Checkout')
                    ->icon('heroicon-o-arrow-right-on-rectangle')
                    ->color('success')
                    ->authorize('update')
                    ->requiresConfirmation(true)
                    ->hidden(fn($record) => $record->status !== 'parked')
                    ->action(function ($record) {
                        $now = now();
                        $entryTime = Carbon::parse($record->entry_time);
                        $parkingFee = $record->parkingFee;
                        if (!$parkingFee) {
                            Notification::make()
                                ->title('Tarif tidak ditemukan!')
                                ->danger()
                                ->send();
                            return;
                        }

                        $durationInHours = ceil($entryTime->diffInMinutes($now) / 60);
                        if ($durationInHours < 1) $durationInHours = 1;

                        // Hitung Total Harga
                        $totalPrice = $parkingFee->hourly_price * $durationInHours;

                        // Update Transaksi
                        $record->update([
                            'exit_time' => $now,
                            'exit_officer_id' => FacadesAuth::user()->id,
                            'total_price' => $totalPrice,
                            'status' => 'completed',
                        ]);
                    })
                    ->successNotificationTitle('Kendaraan berhasil keluar!'),
                Action::make('print_struk')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn($record) => route('parking.print', $record))
                    ->openUrlInNewTab()
                    ->visible(
                        fn($record) =>
                        $record->status === 'completed' &&
                            FacadesAuth::user()->role === 'officer'
                    )->requiresConfirmation(true)
                    ->successNotificationTitle('Struk berhasil dicetak!'),
                // ->successNotificationIcon('heroicon-o-printer')
                // ->successNotificationButton('Tutup')

                EditAction::make()->visible(fn($record) => (FacadesAuth::user()->role === 'officer' && $record->status === 'parked')),
                DeleteAction::make()->visible(fn($record) => (FacadesAuth::user()->role === 'admin' && $record->status === 'parked')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
