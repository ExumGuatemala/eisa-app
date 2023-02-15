<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientTypeResource\Pages;
use App\Filament\Resources\ClientTypeResource\RelationManagers;
use App\Models\ClientType;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientTypeResource extends Resource
{
    protected static ?string $model = ClientType::class;

    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?int $navigationSort = 4;

    protected static ?string $modelLabel = 'Tipo de Cliente';
    protected static ?string $pluralModelLabel = 'Tipos de Cliente';
    protected static ?string $navigationLabel = 'Tipo de Cliente';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageClientTypes::route('/'),
        ];
    }
}
