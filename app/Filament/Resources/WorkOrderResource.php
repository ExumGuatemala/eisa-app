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
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;

use App\Services\WorkOrderService;

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
                TextInput::make('state')
                    ->label("Estado")
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
                TextColumn::make('client_name')
                    ->label("Cliente")
                    ->getStateUsing(function (Model $record) {
                        return $record->quote->client->name;
                    }),
                TextColumn::make('quote_id')
                    ->label("Código de Orden")
                    ->searchable(),
                TextColumn::make('state')
                    ->label('Estado'),
                TextColumn::make('start_date')
                    ->label("Fecha de Inicio")
                    ->dateTime('d/m/Y'),
                TextColumn::make('end_date')
                    ->label("Fecha Aprox. Entrega")
                    ->dateTime('d/m/Y'),
            ])
            ->filters([
                Filter::make('all')
                    ->query(fn (Builder $query): Builder => $query)
                    ->label('Todas')
                    ->default()
                    ->toggle(),
                Filter::make('inProgress')
                    ->query(fn (Builder $query): Builder => $query->where('state', "En Progreso"))
                    ->label('En Progreso')
                    ->toggle(),
                Filter::make('created')
                    ->query(fn (Builder $query): Builder => $query->where('state', "Creada"))
                    ->label('Creada')
                    ->toggle(),
                Filter::make('Finalizada')
                    ->query(fn (Builder $query): Builder => $query->where('state', "Finalizada"))
                    ->label('Finalizada')
                    ->toggle(),
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label("Desde"),
                        Forms\Components\DatePicker::make('created_until')
                            ->label("Hasta"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Action::make("nextStatus")
                    ->label(function (Model $record) {
                        $workOrderService = new WorkOrderService();
                        return "Cambiar a " . $workOrderService->getNextOrderStatus($record->state);
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Cambiar estado')
                    ->modalSubheading('¿Seguro que desea cambiar al siguiente estado?')
                    ->modalButton('Si, seguro')
                    ->action(function (Model $record) {
                        $workOrderService = new WorkOrderService();
                        $workOrderService->changeToNextOrderStatus($record->id, $record->state);
                    })
                    ->hidden(function (Model $record) {
                        $workOrderService = new WorkOrderService();
                        return $workOrderService->getLastWorkOrderState() == $record->state ? true : false;
                    }),
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
