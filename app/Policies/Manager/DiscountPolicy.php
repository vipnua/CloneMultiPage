<?php

namespace App\Policies\Manager;

use App\Model\Discount;
use App\Model\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscountPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->is_admin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(Admin $user)
    {
        if ($user->can('discount_view')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Discount  $discount
     * @return mixed
     */
    public function view(Admin $user, Discount $discount)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        if ($user->can('discount_create')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Discount  $discount
     * @return mixed
     */
    public function update(Admin $user)
    {
        if ($user->can('discount_edit')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Discount  $discount
     * @return mixed
     */
    public function delete(Admin $user)
    {
        if ($user->can('discount_delete')) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Discount  $discount
     * @return mixed
     */
    public function restore(Admin $user, Discount $discount)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Discount  $discount
     * @return mixed
     */
    public function forceDelete(Admin $user, Discount $discount)
    {
        //
    }
}
