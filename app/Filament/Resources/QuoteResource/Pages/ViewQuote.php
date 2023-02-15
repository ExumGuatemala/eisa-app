<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQuote extends ViewRecord
{
    protected static string $resource = QuoteResource::class;
    protected $listeners = ['refresh'=>'refreshForm'];

    public function refreshForm()
    {
        $this->fillForm();
    }


    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
