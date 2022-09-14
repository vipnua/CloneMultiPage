<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use App\Model\Admin;

class RoleSecond extends Role
{
    protected $appends = ['admin'];
    /**
     * The roles that belong to the RoleSecond
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groupermissions()
    {
        return $this->belongsToMany('App\Model\Groupermission', 'groupermission_role', 'role_id', 'groupermission_id');
    }

    public function getAdminAttribute()
    {
        return Admin::role(config('custom.role'))->count();

    }
}
