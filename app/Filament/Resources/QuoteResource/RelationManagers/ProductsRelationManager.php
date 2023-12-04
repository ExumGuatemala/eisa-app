<?php

namespace App\Filament\Resources\QuoteResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\Grid;
use App\Services\QuoteService;
use App\Services\QuotesProductsService;
use App\Services\ProductsPriceTypesService;
use App\Services\ProductService;
use App\Enums\QuoteStateEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProductsRelationManager extends RelationManager
{
    protected static $quoteService;
    protected static $quotesProductsService;
    protected static $productsPriceTypesService;
    protected static $productService;

    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';


    public function __construct() {
        static::$quoteService = new QuoteService();
        static::$quotesProductsService = new QuotesProductsService();
        static::$productsPriceTypesService = new ProductsPriceTypesService();
        static::$productService = new ProductService();
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
                TextColumn::make('quantity')
                    ->label("Cantidad"),
                TextColumn::make('name')
                    ->label("Nombre"),
                TextColumn::make('description')
                    ->label("Descripción")
                    ->limit(25),
                TextColumn::make('width')
                    ->label("Ancho (m)"),
                TextColumn::make('height')
                    ->label("Alto (m)"),
                TextColumn::make('totalm')
                    ->label("Total (m)"),
                TextColumn::make('unitPrice')
                    ->money('gtq', true)
                    ->label("Precio Unitario")
                    ->getStateUsing(function (Model $record, RelationManager $livewire) {
                        return self::$productsPriceTypesService->getProductPrice($record->product_id, $livewire->ownerRecord->pricetype_id);
                    }),
                TextColumn::make('price')
                    ->money('gtq', true)
                    ->label("Total"),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->recordSelectOptionsQuery(fn (Builder $query) => 
                        
                        $query->where('type', 'servicio')
                            ->orWhere(function ($query) {
                            $query->where('type', 'producto')
                                ->where('existence', '>', 0);
                        })
                    )
                    ->modalWidth('4xl')
                    ->form(fn (AttachAction $action): array => [
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                        ])
                        ->schema([
                            $action->getRecordSelect()
                                ->columnSpan('full')
                                ->afterStateUpdated(function (RelationManager $livewire, $state, callable $set) {
                                    //obtener el precio del producto desde el productsPriceTypesService
                                    $productPrice = self::$productsPriceTypesService->getProductPrice($state, $livewire->ownerRecord->pricetype_id);
                                    $set('precioId', $productPrice);
                                    //obtener las existencias del producto desde el productService
                                    $productExistence = self::$productService->getProductExistence($state);
                                    $set('existenceId', $productExistence);
                                })
                                ->reactive(),
                            TextInput::make('precioId')
                                ->label('Precio')
                                ->disabled(),
                            TextInput::make('existenceId')
                                ->label('Existencia')
                                ->disabled(),
                            TextInput::make('quantity')
                                ->required()
                                ->label('Cantidad')
                                ->columnSpan('full')
                                ->default(1)
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $get) {
                                    $quantity = $get('quantity') == '' ? 1 : $get('quantity');
                                    $width = $get('width') == '' ? 1 : $get('width');
                                    $height = $get('height') == '' ? 1 : $get('height');
                                    if($get('height') == '' or $get('width') == ''){
                                        $set('totalm', 0);
                                    } else {
                                        $set('totalm', bcdiv(((($width * $height) * $quantity)),1,2) );
                                    }
                                }),
                            Textarea::make('description')
                                ->label('Descripción')
                                ->columnSpan('full'),
                            TextInput::make('height')
                                ->label("Alto (m)")
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $get) {
                                    $quantity = $get('quantity') == '' ? 1 : $get('quantity');
                                    $width = $get('width') == '' ? 1 : $get('width');
                                    $height = $get('height') == '' ? 1 : $get('height');
                                    if($get('height') == '' or $get('width') == ''){
                                        $set('totalm', 0);
                                    } else {
                                        $set('totalm', bcdiv(((($width * $height) * $quantity)),1,2) );
                                    }
                                }),
                            TextInput::make('width')
                                ->label("Ancho (m)")
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $get) {
                                    $quantity = $get('quantity') == '' ? 1 : $get('quantity');
                                    $width = $get('width') == '' ? 1 : $get('width');
                                    $height = $get('height') == '' ? 1 : $get('height');
                                    if($get('height') == '' or $get('width') == ''){
                                        $set('totalm', 0);
                                    } else {
                                        $set('totalm', bcdiv(((($width * $height) * $quantity)),1,2) );
                                    }
                                }),
                            TextInput::make('totalm')
                                ->label("Total(m2)")
                                ->columnSpan('full')
                                ->disabled(),

                        ])//scheme ends
                    ])//grid ends
                    ->preloadRecordSelect()
                    ->after(function (RelationManager $livewire) {
                        //Update all prices in pivot table only if its price is zero (that means it was recently added)
                        self::$quotesProductsService->updateAllPrices($livewire->ownerRecord->id, $livewire->ownerRecord->pricetype_id);
                        $livewire->emit('refresh');
                    })
                    ->disabled(function (RelationManager $livewire) {
                        return QuoteStateEnum::CREATED === self::$quoteService->getQuoteState($livewire->ownerRecord->id);
                    })
                    ->hidden(
                        function (RelationManager $livewire) {
                            if (QuoteStateEnum::IN_PROGRESS != $livewire->ownerRecord->state){
                                return TRUE;
                            } else {
                                return FALSE;
                            };
                            $livewire->emit('refresh');
                        }),

            ])
            ->actions([
                EditAction::make()
                    ->modalWidth('4xl')
                    ->form(fn (EditAction $action): array => [
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                        ])
                        ->schema([
                            TextInput::make('precioId')
                                ->label('Precio')
                                ->disabled()
                                ->afterStateHydrated(function (TextInput $component, Model $record, RelationManager $livewire){
                                    $component->state(self::$productsPriceTypesService->getProductPrice($record->product_id, $livewire->ownerRecord->pricetype_id));
                                }),
                            TextInput::make('existenceId')
                                ->label('Existencia')
                                ->disabled()
                                ->afterStateHydrated(function (TextInput $component, Model $record){
                                    $component->state(self::$productService->getProductExistence($record->product_id));
                                }),
                            TextInput::make('quantity')
                                ->required()
                                ->label('Cantidad')
                                ->columnSpan('full')
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $get) {
                                    $quantity = $get('quantity') == '' ? 1 : $get('quantity');
                                        $width = $get('width') == '' ? 1 : $get('width');
                                        $height = $get('height') == '' ? 1 : $get('height');
                                        if($get('height') == '' or $get('width') == ''){
                                            $set('totalm', 0);
                                        } else {
                                            $set('totalm', bcdiv(((($width * $height) * $quantity)),1,2) );
                                        }
                                }),
                            Textarea::make('description')
                                ->label('Descripción')
                                ->columnSpan('full'),
                            TextInput::make('height')
                                ->label("Alto (m)")
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $get) {
                                    $quantity = $get('quantity') == '' ? 1 : $get('quantity');
                                    $width = $get('width') == '' ? 1 : $get('width');
                                        $height = $get('height') == '' ? 1 : $get('height');
                                        if($get('height') == '' or $get('width') == ''){
                                            $set('totalm', 0);
                                        } else {
                                            $set('totalm', bcdiv(((($width * $height) * $quantity)),1,2) );
                                        }
                                }),
                            TextInput::make('width')
                                ->label("Ancho (m)")
                                ->reactive()
                                ->afterStateUpdated(function (callable $set, $get) {
                                    $quantity = $get('quantity') == '' ? 1 : $get('quantity');
                                    $width = $get('width') == '' ? 1 : $get('width');
                                    $height = $get('height') == '' ? 1 : $get('height');
                                    if($get('height') == '' or $get('width') == ''){
                                        $set('totalm', 0);
                                    } else {
                                        $set('totalm', bcdiv(((($width * $height) * $quantity)),1,2) );
                                    }
                                }),
                            TextInput::make('totalm')
                                ->label("Total(m2)")
                                ->columnSpan('full')
                                ->disabled(),
                        ])//scheme ends
                    ])//grid ends
                    ->after(function (RelationManager $livewire) {
                        //Update all prices in pivot table only if its price is zero (that means it was recently added)
                        self::$quotesProductsService->updateAllPrices($livewire->ownerRecord->id, $livewire->ownerRecord->pricetype_id);
                        $livewire->emit('refresh');
                    })
                    ->hidden(
                        function (RelationManager $livewire) {
                            //Update all prices in pivot table only if its price is zero (that means it was recently added)
                            if (QuoteStateEnum::IN_PROGRESS != self::$quoteService->getQuoteState($livewire->ownerRecord->id)){
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
                            if (QuoteStateEnum::IN_PROGRESS != self::$quoteService->getQuoteState($livewire->ownerRecord->id)){
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
