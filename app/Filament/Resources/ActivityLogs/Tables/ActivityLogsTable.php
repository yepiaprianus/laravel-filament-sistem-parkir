<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i:s')
                    ->alignCenter(),
                TextColumn::make('causer.name')
                    ->label('User')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Aktivitas')
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->label('Modul')
                    ->formatStateUsing(fn($state) => str_replace('App\Models\\', '', $state))
                    ->alignCenter(),
                TextColumn::make('event')
                    ->label('Kejadian')
                    ->alignCenter()
                    ->color(fn(string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'secondary',
                    })
                    ->badge()
                    ->formatStateUsing(fn(string $state) => ucfirst($state)),
            ])

            ->filters([
                //
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
