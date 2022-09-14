<?php

namespace App\Policies\Manager;

use App\Model\Admin;
use App\Model\RoleSecond;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanPolicy
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
        if ($user->can('planmanager_view')) {
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
        if ($user->can('planmanager_create')) {
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
        if ($user->can('planmanager_edit')) {
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
        if ($user->can('planmanager_delete')) {
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
    public function setDefault(Admin $user)
    {
        if ($user->can('planmanager_setDefault')) {
            return true;
        }
    }

}
