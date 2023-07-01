<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkOrderResource\Pages;
use App\Filament\Resources\WorkOrderResource\RelationManagers;
use App\Models\WorkOrder;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;   
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkOrderResource extends Resource
{
    protected static ?string $model = WorkOrder::class;

    protected static ?string $navigationGroup = 'Administración';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $modelLabel = 'Orden de trabajo';
    protected static ?string $pluralModelLabel = 'Ordenes de trabajo';
    protected static ?string $navigationLabel = 'Ordenes de trabajo';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('start_date')
                    ->required()
                    ->label("Fecha de inicio")
                    ->columnSpan('full'),
                DatePicker::make('deadline')
                    ->required()
                    ->label("Fecha limite")
                    ->columnSpan('full'),
                TextInput::make('client_name')
                    ->required()
                    ->maxLength(255)
                    ->label("Nombre de cliente")
                    ->columnSpan('full'),
                TextInput::make('order_key')
                    ->required()
                    ->maxLength(255)
                    ->label("Codigo de orden")
                    ->columnSpan('full'),
                Textarea::make('description')
                    ->required()
                    ->label("descripcion")
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('start_date')
                    ->label("Fecha de inicio")
                    ->dateTime(),
                TextColumn::make('deadline')
                    ->label("Fecha de entrega")
                    ->dateTime(),
                TextColumn::make('order_key')
                    ->label("Código de orden"),
                TextColumn::make('client_name')
                    ->label("Nombre de cliente"),
                TextColumn::make('description')
                    ->label("Descripcion"),
            
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListWorkOrders::route('/'),
            'create' => Pages\CreateWorkOrder::route('/create'),
            'edit' => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }    
}
