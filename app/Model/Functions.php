<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Functions extends Model
{
    use SoftDeletes;
    protected $table = 'functions';
    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'description',
        'route',
        'controller',
        'icon',
        'status',
        'ordering',
    ];

    public function child() {
        return $this->hasMany('App\Model\Functions','parent_id', 'id')->latest('ordering')->latest();
    }
}
