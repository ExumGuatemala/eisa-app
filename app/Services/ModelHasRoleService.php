<?php

namespace App\Services;

use App\Models\ModelHasRole;
use App\Repositories\ModelHasRoleRepository;

class ModelHasRoleService
{
    protected $modelHasRoleRepository;

    public function __construct()
    {
        $this->modelHasRoleRepository = new ModelHasRoleRepository; 
    }
    
    public function saveModelRoles($user_id, $role_id)
    {
        $priceTypes = $this->modelHasRoleRepository->save($user_id, "App\Models\User", $role_id);
    }

    public function hasAdminPermissions($userId){
        $role = $this->modelHasRoleRepository->getRoleByUserId($userId);
        if ($role->role_id == 1) {
            return true;
        } else {
            return false;
        }
    }

}
