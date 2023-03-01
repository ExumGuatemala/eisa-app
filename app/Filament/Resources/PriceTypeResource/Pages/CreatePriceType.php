<?php

namespace App\Filament\Resources\PriceTypeResource\Pages;

use App\Filament\Resources\PriceTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Services\PriceTypeService;
use App\Models\PriceType;

class CreatePriceType extends CreateRecord
{
    protected static string $resource = PriceTypeResource::class;

    protected function afterCreate(): void
    {
        $id = $this->record->id;
        PriceTypeService::addProductsToPriceType($this->record);
    }
}
