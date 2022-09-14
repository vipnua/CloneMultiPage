<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plans';
    protected $fillable = [
        'uuid',
        'user_id',
        'type',
        'name',
        'price',
        'price_vn',
        'interval',
        'profile',
        'profile_share',
        'default',
        'describe',
        'status',
        'interval_type',
    ];

    public function charges()
    {
        return $this->hasMany('App\Model\Charge');
    }
}
