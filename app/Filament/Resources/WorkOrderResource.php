<?php

namespace App\Filament\Resources;

use App\Models\WorkOrder;

use Filament\Forms;
use Filament\Tables;

use App\Filament\Resources\WorkOrderResource\Pages;
use App\Filament\Resources\WorkOrderResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;

use Filament\Tables\Columns\TextColumn;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkOrderResource extends Resource
{
    protected static ?string $model = WorkOrder::class;

    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $modelLabel = 'Orden de trabajo';
    protected static ?string $pluralModelLabel = 'Ordenes de trabajo';
    protected static ?string $navigationLabel = 'Ordenes de trabajo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('clientId')
                    ->label("Cliente")
                    ->disabled()
                    ->columnSpan('full'),
                TextInput::make('quote_id')
                    ->label("Código de Orden")
                    ->disabled()
                    ->columnSpan('full'),
                DateTimePicker::make('start_date')
                    ->label('Fecha de Inicio')
                    ->disabled(),
                DateTimePicker::make('end_date')
                    ->label('Fecha Aprox. Entrega')
                    ->disabled(),
                Textarea::make('description')
                    ->columnSpan('full')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('quote_id')
                    ->label("Código de Orden")
                    ->searchable(),
                TextColumn::make('client_name')
                    ->label("Cliente")
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return $record->quote->client->name;
                    }),
                TextColumn::make('start_date')
                    ->label("Fecha de Inicio")
                    ->dateTime(),
                TextColumn::make('end_date')
                    ->label("Fecha Aprox. Entrega")
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'view' => Pages\ViewWorkOrder::route('/{record}'),
            'edit' => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }    
}
