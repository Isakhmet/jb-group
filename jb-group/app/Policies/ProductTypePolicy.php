<?php

namespace App\Policies;

use App\Models\ProductType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        $accesses = $user->roles->accesses;

        foreach ($accesses as $access) {
            if (strcmp($access->code, 'directory') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \App\Models\User        $user
     * @param \App\Models\ProductType $productType
     *
     * @return bool
     */
    public function view(User $user, ProductType $productType)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $accesses = $user->roles->accesses;

        foreach ($accesses as $access) {
            if (strcmp($access->code, 'directory') === 0) {
                return true;
            }
        }

        return false;
    }
}
