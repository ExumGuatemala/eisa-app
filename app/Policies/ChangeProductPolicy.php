<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ModelHasRole;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Repositories\ModelHasRoleRepository;

class ChangeProductPolicy
{
    use HandlesAuthorization;
    protected $modelHasRoleRepository;
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->modelHasRoleRepository = new ModelHasRoleRepository; 
    }

    /**
     * Create a new policy instance.
     *
     * @return bool
     */
    public function showChangePriceTypeProduct(User $user){
        $role = $this->modelHasRoleRepository->getRoleByUserId($user->id);
        return $role->role_id == 1;
    }

}
