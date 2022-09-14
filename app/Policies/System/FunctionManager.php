<?php

namespace App\Policies\System;

use App\Model\Functions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FunctionManager
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
        if ($user->can('function_view')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Functions  $functions
     * @return mixed
     */
    public function view(Admin $user)
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
        if ($user->can('function_create')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Functions  $functions
     * @return mixed
     */
    public function update(Admin $user, Functions $functions)
    {
        if ($user->can('function_edit')) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Functions  $functions
     * @return mixed
     */
    public function delete(Admin $user)
    {
        if ($user->can('function_delete')) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Functions  $functions
     * @return mixed
     */
    public function restore(Admin $user, Functions $functions)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\Functions  $functions
     * @return mixed
     */
    public function forceDelete(Admin $user, Functions $functions)
    {
        //
    }
}
