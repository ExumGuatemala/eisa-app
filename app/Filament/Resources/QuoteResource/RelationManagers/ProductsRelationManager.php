<?php

namespace App\Filament\Resources\QuoteResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\EditAction;
use App\Services\QuoteService;
use App\Services\QuotesProductsService;
use App\Enums\QuoteTypeEnum;
use Illuminate\Database\Eloquent\Model;

class ProductsRelationManager extends RelationManager
{
    protected static $quoteService;
    protected static $quotesProductsService;

    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';
    protected bool $allowsDuplicates = true;

    public function __construct() {
        static::$quoteService = new QuoteService();
        static::$quotesProductsService = new QuotesProductsService();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }
    
    protected function isTablePaginationEnabled(): bool 
    {
        return false;
    } 
 

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label("Nombre"),
                TextColumn::make('price')
                    ->money('gtq', true)
                    ->label("Precio"),
                TextColumn::make('quantity')
                    ->label("Cantidad"),
                TextColumn::make('height')
                    ->label("Alto (m)"),
                TextColumn::make('width')
                    ->label("Ancho (m)"),
                TextColumn::make('description')
                    ->label("Descripción")
                    ->wrap(),
                TextColumn::make('subtotal')
                    ->money('gtq', true)
                    ->label("SubTotal")
                    ->getStateUsing(function (Model $record) {
                            return $record->quantity * $record->price;
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('quantity')
                            ->required()
                            ->label('Cantidad')
                            ->default(1),
                        Textarea::make('description')
                            ->label('Descripción'),
                        TextInput::make('height')
                            ->label("Alto (m)")
                            ->reactive()
                            ->default(1)
                            ->afterStateUpdated(function (callable $set, $get) {
                                $width = $get('width') == '' ? 0 : $get('width');
                                $height = $get('height') == '' ? 0 : $get('height');
                                $set('totalm', round($width * $height, 2));
                            }),
                        TextInput::make('width')
                            ->label("Ancho (m)")
                            ->reactive()
                            ->default(1)
                            ->afterStateUpdated(function (callable $set, $get) {
                                $width = $get('width') == '' ? 0 : $get('width');
                                $height = $get('height') == '' ? 0 : $get('height');
                                $set('totalm', round($width * $height, 2));
                            }),
                        TextInput::make('totalm')
                            ->label("Total(m2)")
                            ->disabled(),
                    ])
                    ->preloadRecordSelect()
                    ->after(function (RelationManager $livewire) {
                        //Update all prices in pivot table only if its price is zero (that means it was recently added)
                        self::$quotesProductsService->updateAllPrices($livewire->ownerRecord->id, $livewire->ownerRecord->pricetype_id);
                        $livewire->emit('refresh');
                    })
                    ->disabled(function (RelationManager $livewire) {
                        return QuoteTypeEnum::CREATED === self::$quoteService->getQuoteStatus($livewire->ownerRecord->id);
                    })
                    ->hidden(
                        function (RelationManager $livewire) {
                            if (QuoteTypeEnum::IN_PROGRESS != self::$quoteService->getQuoteStatus($livewire->ownerRecord->id)){
                                return TRUE;
                            } else {
                                return FALSE;
                            };
                            $livewire->emit('refresh');
                        }),

            ])
            ->actions([
                EditAction::make()
                    ->form(fn (EditAction $action): array => [
                        TextInput::make('quantity')
                            ->required()
                            ->label('Cantidad'),
                        Textarea::make('description')
                            ->label('Descripción'),
                        TextInput::make('height')
                            ->label("Alto (m)")
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $get) {
                                $width = $get('width') == '' ? 0 : $get('width');
                                $height = $get('height') == '' ? 0 : $get('height');
                                $set('totalm', round($width * $height, 2));
                            }),
                        TextInput::make('width')
                            ->label("Ancho (m)")
                            ->reactive()
                            ->afterStateUpdated(function (callable $set, $get) {
                                $width = $get('width') == '' ? 0 : $get('width');
                                $height = $get('height') == '' ? 0 : $get('height');
                                $set('totalm', round($width * $height, 2));
                            }),
                        TextInput::make('totalm')
                            ->label("Total(m2)")
                            ->disabled(),
                    ])
                    ->after(function (RelationManager $livewire) {
                        //Update all prices in pivot table only if its price is zero (that means it was recently added)
                        self::$quotesProductsService->updateAllPrices($livewire->ownerRecord->id, $livewire->ownerRecord->pricetype_id);
                        $livewire->emit('refresh');
                    })
                    ->hidden(
                        function (RelationManager $livewire) {
                            //Update all prices in pivot table only if its price is zero (that means it was recently added)
                            if (QuoteTypeEnum::IN_PROGRESS != self::$quoteService->getQuoteStatus($livewire->ownerRecord->id)){
                                return TRUE;
                            } else {
                                return FALSE;
                            };
                            $livewire->emit('refresh');
                        }),
                    
                
                DetachAction::make()
                    ->after(function (RelationManager $livewire) {
                        self::$quotesProductsService->updateAllPrices($livewire->ownerRecord->id, $livewire->ownerRecord->pricetype_id);
                        $livewire->emit('refresh');
                    })
                    ->hidden(
                        function (RelationManager $livewire) {
                            //Update all prices in pivot table only if its price is zero (that means it was recently added)
                            if (QuoteTypeEnum::IN_PROGRESS != self::$quoteService->getQuoteStatus($livewire->ownerRecord->id)){
                                return True;
                            } else {
                                return false;
                            };
                            $livewire->emit('refresh');
                        }),
            ])
            ->bulkActions([
               
            ]);
    }
}
