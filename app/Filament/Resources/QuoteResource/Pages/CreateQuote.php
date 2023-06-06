<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Services\QuoteService;

class CreateQuote extends CreateRecord
{
    protected static string $resource = QuoteResource::class;
    protected function afterCreate(): void
    {
        $id = $this->record->id;
        $this->record->key = $id;
        $this->record->save();
    }

}
