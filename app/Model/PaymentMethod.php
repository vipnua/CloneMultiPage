<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    const METHOD = [
        'bank' => 1,
        'paypal' => 2,
    ];
    const COUNTRY = [
        'VN',
        'ALL',
    ];
    const STATUS = [
        'enable' => 1,
        'disable' => 0,
    ];
    const PUB_ICON_DIRECTORY = 'payment_method';

    protected $table = 'payment_methods';
    protected $fillable = [
        'method',
        'country',
        'info',
        'status',
        'name',
        'icon',
    ];
//    protected $appends = ['count_transaction', 'sum_transaction'];
    protected $casts = [
        'info' => 'array',
    ];


    public static function getType($value)
    {
        return self::TYPE[$value] ?? 0;
    }

    public function payment_transaction()
    {
        return $this->hasMany('App\Model\PaymentTransaction');
    }

    public function getCountTransactionAttribute()
    {
        return $this->payment_transaction()->count();
    }

    public function getSumTransactionAttribute()
    {
        return $this->payment_transaction()->sum('amount');
    }

    public function payment_transaction2()
    {
        return $this->hasMany('App\Model\PaymentTransaction');
    }
//    public function countRow()
//    {
//        return $this->hasMany('App\Model\PaymentTransaction')->whereMonth('created_at',Carbon::now()->month)->count();
//    }
}
