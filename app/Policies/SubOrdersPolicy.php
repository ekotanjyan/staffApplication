<?php

namespace App\Policies;

use App\Suborder;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubOrdersPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function view(User $user, Suborder $suborder)
    {
        return $user->id === $suborder->product->user_id;
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function update(User $user, Suborder $suborder)
    {
        return $user->id === $suborder->product->user->id;
    }
}
