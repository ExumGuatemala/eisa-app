<?php

namespace App\Filament\Resources\QuoteResource\RelationManagers;


use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\EditAction;
use App\Services\QuoteService;
use App\Services\QuotesProductsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static $quoteService;
    protected static $quotesProductsService;

    protected static string $relationship = 'products';
    protected static ?string $recordTitleAttribute = 'name';

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label("ID"),
                TextColumn::make('name')
                    ->label("Nombre"),
                TextColumn::make('price')
                    ->money('gtq', true)
                    ->label("Precio"),
                TextColumn::make('quantity')
                    ->label("Cantidad"),
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
                    ])
                    ->preloadRecordSelect()
                    ->after(function (RelationManager $livewire) {
                        //Update all prices in pivot table only if its price is zero (that means it was recently added)
                        self::$quotesProductsService->updateAllPrices($livewire->ownerRecord->id, $livewire->ownerRecord->pricetype_id);
                        $livewire->emit('refresh');
                    }),

            ])
            ->actions([
                EditAction::make()
                    ->form(fn (EditAction $action): array => [
                        TextInput::make('quantity')
                            ->required()
                            ->label('Cantidad')
                            ->default(1),
                    ])
                    ->after(function (RelationManager $livewire) {
                        //Update all prices in pivot table only if its price is zero (that means it was recently added)
                        self::$quotesProductsService->updateAllPrices($livewire->ownerRecord->id, $livewire->ownerRecord->pricetype_id);
                        $livewire->emit('refresh');
                    }),
                DetachAction::make()
                    ->after(function (RelationManager $livewire) {
                        self::$quotesProductsService->updateAllPrices($livewire->ownerRecord->id, $livewire->ownerRecord->pricetype_id);
                        $livewire->emit('refresh');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
