<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Filament\Resources\QuoteResource\RelationManagers;
use App\Filament\Resources\TextInput\Mask;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Pages\Actions\Action;

use Filament\Tables;
use App\Models\Quote;
use App\Models\QuoteState;
use App\Models\Client;
use App\Models\PriceType;

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
                    }),
                DateTimePicker::make('created_at')
                    ->label('Fecha de Creación')
                    ->disabled()
                    ->displayFormat('d/m/Y H:i:s')
                    ->hiddenOn('create'),
                TextInput::make('status')
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
                TextColumn::make('status')
                    ->label('Estado'),
                TextColumn::make('client_id')
                    ->label('Cliente')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('client', function (Builder $q) use($search) {
                                $q->where('name', 'like', "%{$search}%");
                            });
                    })
                    ->getStateUsing(function (Model $record) {
                        return $record->client->name;
                    }),
                TextColumn::make('total')
                    ->money('gtq', true)
                    ->default(0),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getActions(): array
    {
        return [
            Action::make('delete')
                ->action(fn () => $this->record->delete())
                ->requiresConfirmation(),
        ];
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
