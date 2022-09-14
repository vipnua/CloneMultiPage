<?php

namespace App\Model\Browser;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $table = 'resources';
    protected $fillable = [
        'name',
        'version',
        'description',
        'status',
        'path',
    ];
}
