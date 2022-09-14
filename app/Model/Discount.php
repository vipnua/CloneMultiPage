<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    protected $casts = [
        'data' => 'array',
    ];
    protected $fillable = [
        'id',
        'name',
        'code',
        'date_from',
        'date_to',
        'description',
        'max_use',
        'data',
        'current_use'
    ];


}
