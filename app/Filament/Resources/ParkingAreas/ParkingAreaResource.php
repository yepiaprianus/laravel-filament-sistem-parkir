<?php

namespace App\Filament\Resources\ParkingAreas;

use App\Filament\Resources\ParkingAreas\Pages\CreateParkingArea;
use App\Filament\Resources\ParkingAreas\Pages\EditParkingArea;
use App\Filament\Resources\ParkingAreas\Pages\ListParkingAreas;
use App\Filament\Resources\ParkingAreas\Schemas\ParkingAreaForm;
use App\Filament\Resources\ParkingAreas\Tables\ParkingAreasTable;
use App\Models\ParkingArea;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ParkingAreaResource extends Resource
{
    protected static ?string $model = ParkingArea::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?int $navigationSort = 4;
    protected static ?string $recordTitleAttribute = 'Parking Area';

    public static function canViewAny(): bool
    {
        return in_array(Auth::user()->role, ['admin']);
    }

    public static function getNavigationGroup(): ?string
    {
        // echo Auth::user()->role;
        return 'Management';
    }


    public static function form(Schema $schema): Schema
    {
        return ParkingAreaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ParkingAreasTable::configure($table);
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
            'index' => ListParkingAreas::route('/'),
            'create' => CreateParkingArea::route('/create'),
            'edit' => EditParkingArea::route('/{record}/edit'),
        ];
    }
}
