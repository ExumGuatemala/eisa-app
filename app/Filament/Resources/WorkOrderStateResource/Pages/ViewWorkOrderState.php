<?php

namespace App\Filament\Resources\WorkOrderStateResource\Pages;

use App\Filament\Resources\WorkOrderStateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkOrderState extends ViewRecord
{
    protected static string $resource = WorkOrderStateResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
