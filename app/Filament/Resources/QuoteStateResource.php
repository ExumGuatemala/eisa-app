<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteStateResource\Pages;
use App\Filament\Resources\QuoteStateResource\RelationManagers;
use App\Models\QuoteState;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuoteStateResource extends Resource
{
    protected static ?string $model = QuoteState::class;

    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Estado de Cotización';
    protected static ?string $pluralModelLabel = 'Estados de Cotización';
    protected static ?string $navigationLabel = 'Estado de Cotización';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nombre'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Fecha de Creación'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageQuoteStates::route('/'),
        ];
    }
}
