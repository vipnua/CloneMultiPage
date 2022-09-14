<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{

    protected $table = 'folders';
    protected $fillable = [
        'id',
        'user_id',
        'name',
        'uuid',
    ];

    public function browser()
    {
        return $this->belongsToMany('App\Browser', 'folder_browser', 'folder_id', 'browser_id');
    }

    /* 
    public function sharing()
    {
        return $this->belongsToMany('App\User', 'resource_sharing', 'folder_id', 'user_id')->as('sharing')->withPivot('role');
    } */

    
    /**
     * Get all shared folder.
     */
    public function sharing()
    {
        return $this->morphToMany('App\User',  'userable')->as('sharing')->withPivot('role');
    }
}
