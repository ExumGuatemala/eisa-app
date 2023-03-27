<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use App\Services\QuoteService;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQuote extends ViewRecord
{
    protected static string $resource = QuoteResource::class;
    protected $listeners = ['refresh'=>'refreshForm'];
    protected static $quoteService;

    public function __construct() {
        static::$quoteService = new QuoteService;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['total'] = self::$quoteService->updateTotal($data['id']);
        return $data;
    }

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
