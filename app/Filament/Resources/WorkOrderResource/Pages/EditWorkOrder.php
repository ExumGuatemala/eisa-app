<?php

namespace App\Filament\Resources\WorkOrderResource\Pages;

use App\Filament\Resources\WorkOrderResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkOrder extends EditRecord
{
    protected static string $resource = WorkOrderResource::class;

    protected function getActions(): array
    {
        return [
            //
        ];
    }
}
