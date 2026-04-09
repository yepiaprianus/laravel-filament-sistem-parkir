<?php

namespace App\Filament\Resources\ParkingFees;

use App\Filament\Resources\ParkingFees\Pages\CreateParkingFee;
use App\Filament\Resources\ParkingFees\Pages\EditParkingFee;
use App\Filament\Resources\ParkingFees\Pages\ListParkingFees;
use App\Filament\Resources\ParkingFees\Schemas\ParkingFeeForm;
use App\Filament\Resources\ParkingFees\Tables\ParkingFeesTable;
use App\Models\ParkingFee;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ParkingFeeResource extends Resource
{
    protected static ?string $model = ParkingFee::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        // Admin dan Petugas bisa akses menu Kendaraan
        return in_array(Auth::user()->role, ['admin']);
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Management';
    }

    protected static ?string $recordTitleAttribute = 'Parking Fee';

    public static function form(Schema $schema): Schema
    {
        return ParkingFeeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ParkingFeesTable::configure($table);
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
            'index' => ListParkingFees::route('/'),
            'create' => CreateParkingFee::route('/create'),
            'edit' => EditParkingFee::route('/{record}/edit'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
