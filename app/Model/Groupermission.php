<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Groupermission extends Model
{
    protected $table = 'groupermissions';
    protected $fillable = [
        'id',
        'name',
        'key',
        'description'
    ];

    
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'groupermission_role', 'groupermission_id', 'role_id')->select('roles.id','roles.name');
    }

    
    public function permissions()
    {
        return $this->hasMany(Permission::class, 'groupermission_id', 'id')->select('id','name','groupermission_id');
    }
}
