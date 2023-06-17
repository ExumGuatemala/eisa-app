<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Illuminate\Support\Facades\Hash;
use Filament\Resources\Pages\ManageRecords;
use Spatie\Permission\Models\Role;
use App\Models\ModelHasRole;
use App\Services\ModelHasRoleService;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;
    protected static $modelRoleService;
    public function __construct() {
        static::$modelRoleService = new ModelHasRoleService();
    }
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->mutateFormDataUsing(function (array $data): array {
                $password = $data['password'];
                $data['password'] = Hash::make($password);
                return $data;
            })
            ->after(function () {
                self::$modelRoleService->saveModelRoles(2, $this->cachedForms['mountedActionForm']->model->id);
            }), 
        ];
    }
}
