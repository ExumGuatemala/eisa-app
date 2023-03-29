<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Pages\Actions\CreateAction;
use App\Services\ProductsPriceTypesService;
use App\Models\Product;


class ManageProducts extends ManageRecords
{
    protected static string $resource = ProductResource::class;
    protected static $productsPriceTypesService;
    public $record;

    public function __construct() {
        static::$productsPriceTypesService = new ProductsPriceTypesService();
    }

    protected function getActions(): array
    {
        return [
            CreateAction::make()
                ->action(function (array $data) {
                    $this->record = Product::create($data);
                })
                ->after(function () {
                    self::$productsPriceTypesService->addProductToPriceTypes($this->record->id, $this->record->sale_price);
                }),
        ];
    }
}
