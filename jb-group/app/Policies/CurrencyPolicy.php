<?php

namespace App\Policies;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CurrencyPolicy
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
            if (strcmp($access->code, 'currency') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Currency $currency)
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
            if (strcmp($access->code, 'currency_add') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        $accesses = $user->roles->accesses;

        foreach ($accesses as $access) {
            if (strcmp($access->code, 'currency_edit') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Currency $currency)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Currency $currency)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Currency $currency)
    {
        //
    }
}
