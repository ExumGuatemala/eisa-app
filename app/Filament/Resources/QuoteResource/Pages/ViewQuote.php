<?php

namespace App\Filament\Resources\QuoteResource\Pages;

use App\Filament\Resources\QuoteResource;
use App\Services\QuoteService;
use App\Services\WorkOrderService;
use App\Services\ModelHasRoleService;
use App\Enums\QuoteTypeEnum;
use Filament\Pages\Actions;
use Filament\Forms\Components\Wizard\Step;
use App\Policies\ChangeProductPolicy;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\DatePicker; 
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use App\Models\Client;
use Filament\Notifications\Notification; 

class ViewQuote extends ViewRecord
{
    protected static string $resource = QuoteResource::class;
    protected $listeners = ['refresh'=>'refreshForm'];
    protected static $quoteService;
    protected static $workOrderService;
    protected static $userPolicy;

    

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
                ->hidden(self::$userPolicy->showChangePriceTypeProduct(auth()->user()) && QuoteTypeEnum::IN_PROGRESS != self::$quoteService->getQuoteStatus($this->record->id)),
            Action::make('Cambiar a Creada')
                ->action(function () {
                    self::$quoteService->changeStateToCreated($this->record->id);
                    $this->refreshFormData([
                        'status',
                    ]);
                    $this->fillForm();
                    redirect('admin/quotes/' . $this->record->id);
                })
                ->requiresConfirmation()
                ->modalHeading('Finalizar de llenar y crear cotizacion?')
                ->modalSubheading("Una vez que se cree la cotizacion, ya no se podra cambiar. Seguro que desea continuar?")
                ->modalButton('Si!')
                ->hidden(function () {
                    return QuoteTypeEnum::CREATED === self::$quoteService->getQuoteStatus($this->record->id);
                })
                ->hidden(QuoteTypeEnum::IN_PROGRESS != self::$quoteService->getQuoteStatus($this->record->id) ),
            Action::make('Crear una orden de trabajo')
                ->steps([
                    Step::make('start_date')
                        ->label('Ingrese fecha de inicio')
                        ->description('Fecha de inicio para la orden de trabajo')
                        ->schema([
                            DatePicker::make('start_date')
                                ->required()
                                ->label('Fecha de inicio.')
                        ]),
                    Step::make('deadline')
                        ->label('Ingrese fecha de entreda')
                        ->description('Fecha de entreda para la orden de trabajo')
                        ->schema([
                            DatePicker::make('deadline')
                                ->label('Fecha de entreda.')
                                ->required()
                        ]),
                    Step::make('description')
                        ->label('Descripcion')
                        ->description('Puede editar la descripcion de la orden de trabajo')
                        
                        ->schema([
                            Textarea::make('description')
                                ->label('Descripcion')
                                ->required()
                                ->default(function () {
                                    $this->record->products;
                                    $products = "";
                                    foreach ($this->record->products as $value) {
                                        $products = $products . $value['name'] . "\n";
                                    }
                                    return $products;
                                })
                                
                        ])
                ]) 
                ->startOnStep(1)
                ->action(function ( array $data) {
                    dd($this->record->products);
                    // self::$workOrderService->saveWorkOrder(Client::find($this->record->client_id)->name, $data["description"], $this->record->key, $data["start_date"], $data["deadline"]);
                    // self::$workOrderService->saveWorkOrder(Client::find($this->record->client_id)->name, $data["description"], "example key", $data["start_date"], $data["deadline"]);
                    // Notification::make() 
                    //     ->title('Creada orden de trabajo')
                    //     ->success()
                    //     ->send(); 
                })
                // ->hidden(QuoteTypeEnum::IN_PROGRESS != self::$quoteService->getQuoteStatus($this->record->id) || self::$workOrderService->countByKey("example") > 0),
                ->hidden(QuoteTypeEnum::IN_PROGRESS != self::$quoteService->getQuoteStatus($this->record->id)),
        ];
    }
}
