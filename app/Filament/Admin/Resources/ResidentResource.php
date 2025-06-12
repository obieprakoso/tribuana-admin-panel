<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ResidentResource\Pages;
use App\Filament\Admin\Resources\ResidentResource\RelationManagers;
use App\Models\Resident;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResidentResource extends Resource
{
    protected static ?string $model = Resident::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListResidents::route('/'),
            'create' => Pages\CreateResident::route('/create'),
            'edit' => Pages\EditResident::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->select(['id', 'head_of_family', 'house_number', 'contact', 'status', 'is_deleted']);
    }

    public static function getLabel(): string
    {
        return 'Resident';
    }

    public static function getPluralLabel(): string
    {
        return 'Residents';
    }

    public static function getNavigationLabel(): string
    {
        return 'Residents';
    }

    public static function getModelLabel(): string
    {
        return 'Resident';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['head_of_family', 'house_number'];
    }
}
