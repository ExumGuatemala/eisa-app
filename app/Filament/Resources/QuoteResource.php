<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Filament\Resources\QuoteResource\RelationManagers;
use App\Filament\Resources\TextInput\Mask;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Actions\Action;

use Filament\Tables;
use App\Models\Quote;
use App\Models\Client;
use App\Models\PriceType;
use App\Models\Departamento;
use App\Models\Municipio;

use App\Enums\QuoteStateEnum;
use App\Services\WorkOrderService;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static ?string $navigationGroup = 'Ventas';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    protected static ?string $modelLabel = 'Cotización';
    protected static ?string $pluralModelLabel = 'Cotizaciones';
    protected static ?string $navigationLabel = 'Cotizaciones';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('clientId')
                    ->label('Cliente')
                    ->columnSpan('full')
                    ->searchable()
                    ->options(Client::all()->pluck('name', 'id'))
                    ->relationship('client', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $get) {
                        $client = Client::find(($get('clientId')));
                        $set('pricetypeId', $client->pricetype_id);
                    })
                    ->createOptionForm([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label("Nombre Completo")
                            ->columnSpan('full'),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255)
                            ->label("Correo Electrónico"),
                        TextInput::make('key')
                            ->label("Código")
                            ->disabled()
                            ->afterStateHydrated(function (TextInput $component, $state) {
                                if(!$state){
                                    $component->state(strtoupper(substr(bin2hex(random_bytes(ceil(8 / 2))), 0, 8)));
                                }
                            }),
                        TextInput::make('phone1')
                            ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000-0000'))
                            ->required()
                            ->maxLength(255)
                            ->label("Teléfono 1"),
                        TextInput::make('phone2')
                            ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000-0000'))
                            ->maxLength(255)
                            ->label("Teléfono 2"),
                        Select::make('tipoClienteId')
                            ->label('Tipo de Cliente')
                            ->relationship('type', 'name'),
                        Select::make('tipoPrecioId')
                            ->label('Tipo de Precio')
                            ->relationship('priceType', 'name'),
                        TextInput::make('address')
                            ->required()
                            ->columnSpan('full')
                            ->label("Dirección"),

                        Select::make('departamentoId')
                            ->label('Departamento')
                            ->afterStateHydrated(function (Model|null $record, Select $component) {
                                $municipio = $record == null ? $record : Municipio::find($record->municipio_id);
                                if(!$municipio){
                                    $component->state(13);
                                } else {
                                    $component->state($municipio->departamento->id);
                                }
                            })
                            ->options(Departamento::all()->pluck('name','id')->toArray())
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('municipioId', null)),

                        Select::make('municipioId')
                            ->label('Municipio')
                            ->relationship('municipio', 'name')
                            ->options(function (callable $get) {
                                $departamento = Departamento::find($get('departamentoId'));

                                if(!$departamento){
                                    return Municipio::all()->pluck('name','id');
                                }

                                return $departamento->municipios->pluck('name','id');

                            }),
                    ]),
                TextInput::make('key')
                    ->label("Código")
                    ->disabled()
                    ->default('Asignado cuando se cree'),
                DateTimePicker::make('created_at')
                    ->label('Fecha de Creación')
                    ->disabled()
                    ->displayFormat('d/m/Y H:i:s')
                    ->hiddenOn('create'),
                TextInput::make('state')
                    ->label('Estado')
                    ->disabled()
                    ->hiddenOn('create'),
                TextInput::make('total')
                    ->default(0)
                    ->disabled()
                    ->mask(fn (TextInput\Mask $mask) => $mask->money(prefix: 'Q.', thousandsSeparator: ',', decimalPlaces: 2))
                    ->hiddenOn('create'),
                Select::make('pricetypeId')
                    ->label('Tipo de Precio')
                    ->options(PriceType::all()->pluck('name', 'id'))
                    ->relationship('priceType', 'name')
                    ->afterStateHydrated(function (Select $component, $state) {
                        if(!$state){
                            $component->state(1);
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client_id')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('client', function (Builder $q) use($search) {
                                $q->where('name', 'like', "%{$search}%");
                            });
                    })
                    ->getStateUsing(function (Model $record) {
                        return $record->client->name;
                    }), 
                TextColumn::make('key')
                    ->label('Codigo'),
                TextColumn::make('state')
                    ->label('Estado'),
                   
                TextColumn::make('total')
                    ->money('gtq', true)
                    ->default(0),
                TextColumn::make('created_at')
                    ->label("Fecha de Creación")
                    ->dateTime('d/m/Y H:i:s'),
            ])
            ->filters([
                Filter::make('all')
                    ->query(fn (Builder $query): Builder => $query)
                    ->label('Todas')
                    ->default()
                    ->toggle(),
                Filter::make('inProgress')
                    ->query(fn (Builder $query): Builder => $query->where('state', QuoteStateEnum::IN_PROGRESS))
                    ->label('En Progreso')
                    ->toggle(),
                Filter::make('created')
                    ->query(fn (Builder $query): Builder => $query->where('state', QuoteStateEnum::CREATED))
                    ->label('Creada')
                    ->toggle(),
                Filter::make('approved')
                    ->query(fn (Builder $query): Builder => $query->where('state', QuoteStateEnum::APPROVED))
                    ->label('Aprobada')
                    ->toggle(),
                Filter::make('applied')
                    ->query(fn (Builder $query): Builder => $query->where('state', QuoteStateEnum::APPLIED))
                    ->label('Aplicada')
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
                Action::make("viewWorkOrder")
                    ->label("Ver Orden de Trabajo")
                    ->action(function (Model $record) {
                        $workOrderService = new WorkOrderService();
                        $id = $workOrderService->getByQuoteId($record->id)->id;
                        redirect()->intended('/admin/work-orders/'.str($id));
                    })
                    ->hidden(function (Model $record) {
                        return QuoteStateEnum::APPLIED != $record->state;
                    }),
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
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'view' => Pages\ViewQuote::route('/{record}'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
        ];
    }
}
