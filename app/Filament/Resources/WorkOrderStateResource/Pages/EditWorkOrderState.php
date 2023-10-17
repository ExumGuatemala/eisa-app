<?php

namespace App\Filament\Resources\WorkOrderStateResource\Pages;

use App\Filament\Resources\WorkOrderStateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkOrderState extends EditRecord
{
    protected static string $resource = WorkOrderStateResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
