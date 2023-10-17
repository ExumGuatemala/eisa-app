<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use Filament\Resources\Pages\ViewRecord;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;

use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\EditAction;

use App\Services\QuoteService;
use App\Services\WorkOrderService;
use App\Enums\QuoteStateEnum;

use App\Policies\ChangeProductPolicy;
use Filament\Notifications\Notification; 

class ViewQuote extends ViewRecord
{
    protected static string $resource = QuoteResource::class;

    protected static $quoteService;
    protected static $workOrderService;
    protected static $userPolicy;

    protected $listeners = ['refresh'=>'refreshForm'];
    
    public function __construct() {
        static::$quoteService = new QuoteService;
        static::$workOrderService = new WorkOrderService;
        static::$userPolicy = new ChangeProductPolicy;
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
                ->label('Cambiar Tipo de Precio')
                ->hidden(self::$userPolicy->showChangePriceTypeProduct(auth()->user()) && QuoteStateEnum::IN_PROGRESS != self::$quoteService->getQuoteState($this->record->id)),
                
            Action::make('Cambiar a Creada')
                ->label('Cambiar a Creada')
                ->action(function () {
                    self::$quoteService->changeStateTo($this->record, QuoteStateEnum::CREATED);
                    $this->refreshFormData([
                        'state',
                    ]);
                    $this->fillForm();
                    redirect('admin/quotes/' . $this->record->id);
                })
                ->requiresConfirmation()
                ->modalHeading('Finalizar de llenar y crear cotizacion?')
                ->modalSubheading("Una vez que se cree la cotizacion, ya no se podra cambiar. Seguro que desea continuar?")
                ->modalButton('Si!')
                ->hidden(function () {
                    return QuoteStateEnum::IN_PROGRESS != $this->record->state;
                }),
            
            Action::make('createWorkOrder')
                ->color('danger')
                ->label('Aplicar Cotización')
                ->modalHeading('Aplicar Cotización')
                ->hidden(function () {
                    return self::$workOrderService->showCreateButton($this->record);
                })
                ->action(function ( array $data) {
                    self::$workOrderService->saveWorkOrder($data["description"], $this->record->key, $data["start_date"], $data["end_date"]);
                    self::$quoteService->changeStateTo($this->record, QuoteStateEnum::APPLIED);
                    redirect('admin/work-orders');
                })
                ->form([
                    DatePicker::make('start_date')
                        ->label('Fecha de Inicio')
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('Fecha de Entrega')
                        ->required(),
                    Textarea::make('description')
                        ->label('Descripción de la Orden de Trabajo')
                        ->required()
                        ->default(function () {
                            $this->record->products;
                            $products = "";
                            foreach ($this->record->products as $value) {
                                $products = $products . $value['name'] . "\n";
                            }
                            return $products;
                        }),
                ]),
        ];
    }
}
