<?php

namespace App\Policies\System;

use App\Model\RoleSecond;
use App\User;
use App\Model\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
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
        if ($user->can('role_view')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\RoleSecond  $roleSecond
     * @return mixed
     */
    public function view(Admin $user, RoleSecond $roleSecond)
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
        if ($user->can('role_create')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\RoleSecond  $roleSecond
     * @return mixed
     */
    public function update(Admin $user)
    {
        if ($user->can('role_edit')) {
        return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\RoleSecond  $roleSecond
     * @return mixed
     */
    public function delete(Admin $user)
    {
        if ($user->can('role_delete')) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\RoleSecond  $roleSecond
     * @return mixed
     */
    public function restore(Admin $user, RoleSecond $roleSecond)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Model\RoleSecond  $roleSecond
     * @return mixed
     */
    public function forceDelete(Admin $user, RoleSecond $roleSecond)
    {
        //
    }

    public function viewAssign(Admin $user)
    {
        if ($user->can('role_viewassign')) {
            return true;
        }
    }

    public function editAssign(Admin $user)
    {
        if ($user->can('role_editassign')) {
            return true;
        }
    }
}
