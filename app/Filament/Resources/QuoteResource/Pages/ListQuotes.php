<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\Layout;

class ListQuotes extends ListRecords
{
    protected static string $resource = QuoteResource::class;
 
    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
