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
use App\Services\QuoteService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static $quoteService;

    protected static string $relationship = 'products';
    protected static ?string $recordTitleAttribute = 'name';

    public function __construct() {
        static::$quoteService = new QuoteService();
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
                TextColumn::make('name')
                    ->label("Nombre"),
                TextColumn::make('sale_price')
                    ->money('gtq', true)
                    ->label("Precio")
                    ->getStateUsing(function (Model $record, RelationManager $livewire) {
                        return self::$quoteService->getProductPriceTypePrice($livewire->ownerRecord->client_id, $record->product_id);
                    }),
                TextColumn::make('quantity')
                    ->label("Cantidad"),
                TextColumn::make('subtotal')
                    ->money('gtq', true)
                    ->label("SubTotal")
                    ->getStateUsing(function (Model $record, RelationManager $livewire) {
                        return $record->quantity * self::$quoteService->getProductPriceTypePrice($livewire->ownerRecord->client_id, $record->product_id);
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
                        self::$quoteService->addToTotal($livewire->ownerRecord, $livewire->mountedTableActionData['recordId'], $livewire->mountedTableActionData['quantity']);
                        $livewire->emit('refresh');
                    }),
            ])
            ->actions([
                DetachAction::make()
                    ->before(function (RelationManager $livewire) {
                        self::$quoteService->substractFromTotal($livewire->ownerRecord, $livewire->cachedMountedTableActionRecord['product_id'], $livewire->cachedMountedTableActionRecord['quantity']);
                        $livewire->emit('refresh');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}
