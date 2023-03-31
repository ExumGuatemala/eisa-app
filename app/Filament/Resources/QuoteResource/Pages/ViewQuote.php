<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use App\Services\QuoteService;
use App\Enums\QuoteTypeEnum;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\EditAction;
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
            EditAction::make()
                ->label('Cambiar Tipo de Precio'),
            Action::make('Cambiar a Creada')
                ->action(function () {
                    self::$quoteService->changeStateToCreated($this->record->id);
                    $this->refreshFormData([
                        'status',
                    ]);
                    $this->fillForm();
                })
                ->requiresConfirmation()
                ->modalHeading('Finalizar de llenar y crear cotizacion?')
                ->modalSubheading("Una vez que se cree la cotizacion, ya no se podra cambiar. Seguro que desea continuar?")
                ->modalButton('Si!')
                ->hidden(function () {
                    return QuoteTypeEnum::CREATED === self::$quoteService->getQuoteStatus($this->record->id);
                }),
        ];
    }
}
