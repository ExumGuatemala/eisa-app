<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use App\Models\Client;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Services\QuoteService;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class CreateQuote extends CreateRecord
{
    protected static string $resource = QuoteResource::class;
    
    protected function beforeCreate(): void
    {
        $client = Client::where("id", $this->data["clientId"])->first();
        if ($client->state == "Deshabilitado") {
            Notification::make()
                ->warning()
                ->title('Cliente Deshabilitado')
                ->body('El cliente que ha elegido esta deshabilitado. Por favor elija otro.')
                ->persistent()
                ->send();
        
            $this->halt();
        }
    }
    
    protected function afterCreate(): void
    {
        $id = $this->record->id;
        $this->record->key = $id;
        $this->record->save();
    }

}
