<?php

namespace App\Filament\Resources\QuoteStateResource\Pages;

use App\Filament\Resources\QuoteStateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageQuoteStates extends ManageRecords
{
    protected static string $resource = QuoteStateResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
