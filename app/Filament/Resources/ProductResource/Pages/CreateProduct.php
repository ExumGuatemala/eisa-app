<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Services\ProductsPriceTypesService;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected static $productsPriceTypesService;

    public function __construct() {
        static::$productsPriceTypesService = new ProductsPriceTypesService();
    }

    protected function afterCreate(): void
    {
        self::$productsPriceTypesService->addProductToPriceTypes($this->record->id, $this->record->sale_price);
    }
}
