<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use App\Models\Client;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationGroup = 'Ventas';
    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $modelLabel = 'Cliente';
    protected static ?string $pluralModelLabel = 'Clientes';
    protected static ?string $navigationLabel = 'Clientes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label("Nombre"),
                TextInput::make('lastname')
                    ->required()
                    ->maxLength(255)
                    ->label("Apellido"),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->label("Correo Electrónico"),
                TextInput::make('key')
                    ->maxLength(255)
                    ->label("Código"),
                TextInput::make('address')
                    ->maxLength(255)
                    ->label("Dirección"),
                TextInput::make('phone1')
                    ->tel()
                    ->required()
                    ->maxLength(255)
                    ->label("Teléfono 1"),
                TextInput::make('phone2')
                    ->tel()
                    ->maxLength(255)
                    ->label("Teléfono 2"),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label("Nombre"),
                Tables\Columns\TextColumn::make('lastname')
                    ->label("Apellido"),
                Tables\Columns\TextColumn::make('key')
                    ->label("Código"),
                Tables\Columns\TextColumn::make('phone1')
                    ->label("Teléfono 1"),
                    Tables\Columns\TextColumn::make('email')
                    ->label("Correo Electrónico"),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label("Fecha de Creación"),
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
            'index' => Pages\ManageClients::route('/'),
        ];
    }
}
