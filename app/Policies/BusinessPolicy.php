<?php

namespace App\Policies;

use App\Business;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinessPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Business  $business
     * @return mixed
     */
    public function view(User $user, Business $business)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Business  $business
     * @return mixed
     */
    public function update(User $user, Business $business)
    {
        return $user->id === $business->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Business  $business
     * @return mixed
     */
    public function delete(User $user, Business $business)
    {
        return $user->id === $business->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Business  $business
     * @return mixed
     */
    public function restore(User $user, Business $business)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Business  $business
     * @return mixed
     */
    public function forceDelete(User $user, Business $business)
    {
        //
    }
}
