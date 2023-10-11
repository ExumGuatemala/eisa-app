<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Models\Quote;

use App\Filament\Resources\WorkOrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkOrder extends ViewRecord
{
    protected static string $resource = WorkOrderResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['clientId'] = Quote::where('id', $data['quote_id'])->with('client')->first()->client->name;
     
        return $data;
    }

    protected function getActions(): array
    {
        return [
            //
        ];
    }
}
