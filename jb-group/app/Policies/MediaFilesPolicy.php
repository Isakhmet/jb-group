<?php

namespace App\Policies;

use App\Models\MediaFiles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaFilesPolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MediaFiles  $mediaFiles
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, MediaFiles $mediaFiles)
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
            if (strcmp($access->code, 'media_create') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MediaFiles  $mediaFiles
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, MediaFiles $mediaFiles)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        $accesses = $user->roles->accesses;

        foreach ($accesses as $access) {
            if (strcmp($access->code, 'media_create') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MediaFiles  $mediaFiles
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, MediaFiles $mediaFiles)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MediaFiles  $mediaFiles
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, MediaFiles $mediaFiles)
    {
        //
    }
}
