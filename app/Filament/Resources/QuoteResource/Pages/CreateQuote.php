<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Services\QuoteService;

class CreateQuote extends CreateRecord
{
    protected static $quoteService;
    public function __construct() {
        static::$quoteService = new QuoteService();
    }
    protected static string $resource = QuoteResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['key'] = self::$quoteService->setAKey($data['key']);
        return $data;
    }
}
