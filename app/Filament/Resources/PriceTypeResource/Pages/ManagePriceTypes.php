<?php

namespace App\Filament\Resources\PriceTypeResource\Pages;

use App\Filament\Resources\PriceTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePriceTypes extends ManageRecords
{
    protected static string $resource = PriceTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
