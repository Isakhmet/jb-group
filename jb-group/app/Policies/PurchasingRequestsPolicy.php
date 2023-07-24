<?php

namespace App\Policies;

use App\Models\PurchasingRequests;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchasingRequestsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        $accesses = $user->roles->accesses;

        foreach ($accesses as $access) {
            if (strcmp($access->code, 'purchasing_view_any') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchasingRequests  $purchasingRequests
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        $accesses = $user->roles->accesses;

        foreach ($accesses as $access) {
            if (strcmp($access->code, 'purchasing_view') === 0) {
                return true;
            }
        }

        return false;
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
            if (strcmp($access->code, 'purchasing_create') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchasingRequests  $purchasingRequests
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        $accesses = $user->roles->accesses;

        foreach ($accesses as $access) {
            if (strcmp($access->code, 'purchasing_update') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchasingRequests  $purchasingRequests
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, PurchasingRequests $purchasingRequests)
    {
        $accesses = $user->roles->accesses;

        foreach ($accesses as $access) {
            if (strcmp($access->code, 'purchasing_delete') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchasingRequests  $purchasingRequests
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, PurchasingRequests $purchasingRequests)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchasingRequests  $purchasingRequests
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, PurchasingRequests $purchasingRequests)
    {
        //
    }
}
