<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasRoles,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'setting','uuid','status','plan_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function browser()
    {
        return $this->hasMany('App\Browser', 'user_id', 'id');
    }


   /*  public function sharing()
    {
        return $this->belongsToMany('App\Browser', 'resource_sharing', 'user_id', 'browser_id')->as('sharing')->withPivot('role');
    } */

    /**
     * Get all of the posts that are assigned this tag.
     */
    public function sharing()
    {
        return $this->morphedByMany('App\Browser', 'userable')->as('sharing')->withPivot('role');
    }

    /*     public function folder_sharing()
    {
        return $this->belongsToMany('App\Model\Folder', 'resource_sharing', 'user_id', 'folder_id')->as('folder_sharing')->withPivot('role');
    } */

    /**
     * Get all of the posts that are assigned this tag.
     */
    public function folder_sharing()
    {
        return $this->morphedByMany('App\Model\Folder', 'userable')->as('folder_sharing')->withPivot('role');
    }

    /**
     * Get all of the folders for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function folders()
    {
        return $this->hasMany('App\Model\Folder');
    }

    public function paymentTransactions()
    {
        return $this->hasMany('App\Model\PaymentTransaction');
    }

}
