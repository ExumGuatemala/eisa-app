<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Models\Quote;

use App\Filament\Resources\WorkOrderResource;
use Filament\Pages\Actions;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Services\WorkOrderService;

class ViewWorkOrder extends ViewRecord
{
    protected static string $resource = WorkOrderResource::class;   
    protected static $workOrderService;

    public function __construct() {
        static::$workOrderService = new WorkOrderService;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['clientId'] = Quote::where('id', $data['quote_id'])->with('client')->first()->client->name;
     
        return $data;
    }

    protected function getActions(): array
    {
        return [
            EditAction::make()
                ->label('Agregar Imagenes'),
            Action::make("nextStatus")
                ->label(function () {
                    return "Cambiar a " . self::$workOrderService->getNextOrderStatus($this->record->state);
                })
                ->requiresConfirmation()
                ->modalHeading('Cambiar estado')
                ->modalSubheading('¿Seguro que desea cambiar al siguiente estado?')
                ->modalButton('Si, seguro')
                ->action(function () {
                    self::$workOrderService->changeToNextOrderStatus($this->record->id, $this->record->state);
                    redirect()->intended('/admin/work-orders/'.str($this->record->id));
                })
                ->hidden(function () {
                    $workOrderService = new WorkOrderService();
                    return $workOrderService->getLastWorkOrderState() == $this->record->state ? true : false;
                }),
        ];
    }
}
