<?php

namespace App\Filament\Resources\PriceTypeResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TextInput\Mask;
use App\Services\QuoteService;
use App\Services\QuotesProductsService;
use App\Models\QuotesProducts;

class ProductsRelationManager extends RelationManager
{
    protected static ?string $model = QuotesProducts::class;
    protected static string $relationship = 'products';
    protected static ?string $recordTitleAttribute = 'name';
    protected static $quoteService;
    protected static $quotesProductsService;
    protected $currentProduct;
     
    public function __construct() {
        static::$quoteService = new QuoteService();
        static::$quotesProductsService = new QuotesProductsService();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('price')
                    ->default(0)
                    ->mask(fn (TextInput\Mask $mask) => $mask->money(prefix: 'Q.', thousandsSeparator: ',', decimalPlaces: 2))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('price')
                    ->money('gtq', true)
                    ->label("Precio"),
            ])
            ->filters([
            ])
            ->headerActions([
            ])
            ->actions([ 
                Tables\Actions\EditAction::make()
                ->after(function (RelationManager $livewire) {
                    self::$quoteService->updateProductQuotePrices($livewire->mountedTableActionData['pricetype_id'],$livewire->mountedTableActionData['id'], $livewire->mountedTableActionData['price']);
                }),
            ])
            ->bulkActions([
            ]);
    }
}
