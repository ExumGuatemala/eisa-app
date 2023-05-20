<?php

namespace App\Filament\Resources\PriceTypeResource\Pages;

use App\Filament\Resources\PriceTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPriceType extends ViewRecord
{
    protected static string $resource = PriceTypeResource::class;
    
    // public function __construct() {
    //     static::$quotesProductsService = new QuotesProductsService;
    // }

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
