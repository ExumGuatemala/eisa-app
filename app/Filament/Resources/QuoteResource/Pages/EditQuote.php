<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

use App\Services\QuotesProductsService;

class EditQuote extends EditRecord
{
    protected static string $resource = QuoteResource::class;
    protected static $quotesProductsService;
    protected $listeners = ['refresh'=>'refreshForm'];

    public function __construct() {
        static::$quotesProductsService = new QuotesProductsService;
    }

    public function refreshForm()
    {
        $this->fillForm();
    }

    protected function afterSave(): void
    {
        self::$quotesProductsService->updateAllPrices($this->record->id,$this->record->pricetype_id);
    }

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
