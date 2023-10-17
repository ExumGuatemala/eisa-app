<?php

namespace App\Filament\Resources;

use App\Models\WorkOrderState;

use App\Filament\Resources\WorkOrderStateResource\Pages;
use App\Filament\Resources\WorkOrderStateResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;

use Filament\Forms;
use Filament\Forms\Components\TextInput;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkOrderStateResource extends Resource
{
    protected static ?string $model = WorkOrderState::class;

    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Estado de Orden de Trabajo';
    protected static ?string $pluralModelLabel = 'Estados de Orden de Trabajo';
    protected static ?string $navigationLabel = 'Estado de Orden de Trabajo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label("Nombre")
                    ->required(),
                TextInput::make('order')
                    ->label("Orden")
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label("Nombre"),
                TextColumn::make('order')
                    ->label("Orden"),
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkOrderStates::route('/'),
            'create' => Pages\CreateWorkOrderState::route('/create'),
            'view' => Pages\ViewWorkOrderState::route('/{record}'),
            'edit' => Pages\EditWorkOrderState::route('/{record}/edit'),
        ];
    }    
}
