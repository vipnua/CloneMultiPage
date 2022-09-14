<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Charge extends Model
{
    use SoftDeletes;
    protected $table = 'charges';
    protected $appends = ['status'];
    protected $fillable = [
        'uuid',
        'type',
        'name',
        'price',
        'interval',
        'profile',
        'profile_share',
        'activated_on',
        'expires_on',
        'plan_id',
        'user_id',
        'deleted_at',
    ];

    public function getStatusAttribute()
    {
        $charge_status = 3;
        $date = Carbon::now();
        $expired = Carbon::create($this->expires_on);
        if ($expired->gt($date)) {
            return ($this->activated_on)?1:2;
        }
        return $charge_status;
    }

    public function plan()
    {
        return $this->belongsTo('App\Model\Plan');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function paymentTransaction()
    {
        return $this->hasOne('App\Model\PaymentTransaction','charge_id','id');
    }
}
