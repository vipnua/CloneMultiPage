<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table = 'versions';
    protected $fillable = [
        'uuid',
        'name',
        'version',
        'description',
        'path',
        'remove_file',
    ];
    
}
