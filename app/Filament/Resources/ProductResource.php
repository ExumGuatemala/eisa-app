<?php

namespace App\Filament\Resources;

use App\Models\Product;
use App\Enums\ProductTypeEnum;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Closure;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationGroup = 'Ventas';
    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $modelLabel = 'Producto';
    protected static ?string $pluralModelLabel = 'Productos';
    protected static ?string $navigationLabel = 'Productos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label("Nombre")
                    ->required()
                    ->maxLength(255)
                    ->columnSpan("full"),
                TextInput::make('sale_price')
                    ->required()
                    ->mask(fn (TextInput\Mask $mask) => $mask->money(prefix: 'Q.', thousandsSeparator: ',', decimalPlaces: 2))
                    ->label("Precio de Venta"),
                TextInput::make('order')
                    ->numeric()
                    ->maxLength(255)
                    ->label("Orden de Visualización"),
                Select::make('type')
                    ->required()
                    ->label("Tipo de Producto")
                    ->options([
                        ProductTypeEnum::SERVICE => 'Productos y Servicios',
                        ProductTypeEnum::PRODUCT => 'Producto Físico',
                    ])
                    ->reactive(),
                TextInput::make('existence')
                    ->numeric()
                    ->label("Existencia")
                    ->hidden(
                        fn (Closure $get): bool => $get('type') != ProductTypeEnum::PRODUCT
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label("Nombre")
                    ->searchable(['name']),
                TextColumn::make('sale_price')
                    ->money('gtq', true)
                    ->label("Precio de Venta"),
                BadgeColumn::make('type')
                    ->label("Tipo")
                    ->color(static function ($state): string {
                        if ($state === ProductTypeEnum::PRODUCT) {
                            return 'success';
                        }
                        
                        return 'danger';
                    }),
                TextColumn::make('existence')
                    ->label("Existencia"),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->label("Fecha de Creación"),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }    
}
