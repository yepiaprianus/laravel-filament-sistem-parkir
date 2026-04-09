<?php

namespace App\Filament\Widgets;

use App\Models\ParkingTransaction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class RecentTransactionsTable extends TableWidget
{
    protected static ?string $heading = 'Daftar Transaksi Hari Ini';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $user = FacadesAuth::user();
        return $table
            ->query(
                fn(): Builder => ParkingTransaction::query()
                    ->whereDate('entry_time', today())
                    ->when($user->role === 'officer', function ($query) use ($user) {
                        return $query->where('entry_officer_id', $user->id);
                    })
                    ->latest()
            )
            ->columns([
                TextColumn::make('ticket_number')
                    ->label('Nomor Tiket')
                    ->searchable(),
                TextColumn::make('vehicle.plate_number')
                    ->label('Nomor Plat')
                    ->searchable()
                    ->alignCenter()
                    ->color('primary')
                    ->badge()
                    ->sortable(),
                TextColumn::make('vehicle.vehicleType.name')
                    ->label('Jenis Kendaraan')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('entry_time')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y H:i:s')
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('d M Y H:i:s') : '-')
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('exit_time')
                    ->label('Waktu Keluar')
                    ->dateTime('d M Y H:i:s')
                    ->alignCenter()
                    ->color('success')
                    ->placeholder('-')
                    ->formatStateUsing(fn($state) => $state ? Carbon::parse($state)->format('d M Y H:i:s') : '-')
                    ->sortable(),
                TextColumn::make('total_price')
                    ->money()
                    ->label('Total Harga')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        'parked' => 'warning',
                        'completed' => 'success',
                        'lost_ticket' => 'danger',
                    })

                    ->badge(),
                TextColumn::make('entryOfficer.name')
                    ->label('Petugas Masuk')
                    ->alignCenter()
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('exitOfficer.name')
                    ->label('Petugas Keluar')
                    ->alignCenter()
                    ->placeholder('-')
                // ->sortable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
