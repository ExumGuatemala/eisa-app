<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceTypeResource\Pages;
use App\Filament\Resources\PriceTypeResource\RelationManagers;
use App\Models\PriceType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PriceTypeResource extends Resource
{
    protected static ?string $model = PriceType::class;

    protected static ?string $navigationGroup = 'Ventas';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Tipo de Precio';
    protected static ?string $pluralModelLabel = 'Tipos de Precio';
    protected static ?string $navigationLabel = 'Tipo de Precio';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPriceTypes::route('/'),
            'create' => Pages\CreatePriceType::route('/create'),
            'view' => Pages\ViewPriceType::route('/{record}'),
            'edit' => Pages\EditPriceType::route('/{record}/edit'),
        ];
    }
}
