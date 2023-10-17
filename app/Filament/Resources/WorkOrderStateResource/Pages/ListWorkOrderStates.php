<?php

namespace App\Filament\Resources\WorkOrderStateResource\Pages;

use App\Filament\Resources\WorkOrderStateResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\WorkOrderState;
use Illuminate\Database\Eloquent\Builder;

class ListWorkOrderStates extends ListRecords
{
    protected static string $resource = WorkOrderStateResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return WorkOrderState::query()->orderBy('order');
    }

    protected function getTableReorderColumn(): ?string
    {
        return 'order';
    }
}
