<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Browser extends Model
{
    use SoftDeletes;
    protected $appends = ['browser_id'];
    protected $table = 'browsers';

    protected $fillable = [
        'id',
        'uuid',
        'config',
        'user_id',
        'directory',
        'file_name',
        'can_be_running',
    ];

    protected $casts = [
        'config' => 'array',
        'can_be_running' => 'boolean',
    ];


    /* public function sharing()
    {
        return $this->belongsToMany('App\User', 'resource_sharing', 'browser_id', 'user_id')->as('sharing')->withPivot('role');
    } */

    /**
     * Get all shared browser.
     */
    public function sharing()
    {
        return $this->morphToMany('App\User',  'userable')->as('sharing')->withPivot('role');
    }
    
    public function folders()
    {
        return $this->belongsToMany('App\Model\Folder', 'folder_browser', 'browser_id', 'folder_id');
    }

    public function getBrowserIdAttribute()
    {

        return $this->id;

    }
    
}