<?php

namespace App\Filament\Resources\ParkingTransactions;

use App\Filament\Resources\ParkingTransactions\Pages\CreateParkingTransaction;
use App\Filament\Resources\ParkingTransactions\Pages\EditParkingTransaction;
use App\Filament\Resources\ParkingTransactions\Pages\ListParkingTransactions;
use App\Filament\Resources\ParkingTransactions\Schemas\ParkingTransactionForm;
use App\Filament\Resources\ParkingTransactions\Tables\ParkingTransactionsTable;
use App\Models\ParkingTransaction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ParkingTransactionResource extends Resource
{
    protected static ?string $model = ParkingTransaction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static ?string $recordTitleAttribute = 'Transaction';

    // protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationGroup(): ?string
    {
        return 'Operations';
    }
    protected static ?int $navigationSort = 7;
    public static function getNavigationBadge(): ?string
    {
        $query = static::getModel()::query()->where('status', 'parked');
        $user = Auth::user();
        if ($user->role === 'officer') {
            return $query->where('entry_officer_id', $user->id)->count();
        }
        return $query->count();
    }
    public static function canViewAny(): bool
    {
        return true; // Semua role bisa melihat transaksi parkir
    }

    public static function canCreate(): bool
    {
        return Auth::user()->role === 'officer';
    }

    public static function form(Schema $schema): Schema
    {
        return ParkingTransactionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ParkingTransactionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListParkingTransactions::route('/'),
            'create' => CreateParkingTransaction::route('/create'),
            'edit' => EditParkingTransaction::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user->role === 'officer') {
            return $query->where('entry_officer_id', $user->id)
                ->orWhere('exit_officer_id', $user->id)->orderByDesc('created_at');
        }
        return $query->orderByDesc('created_at');
    }
}
