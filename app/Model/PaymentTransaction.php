<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{

    const CURRENCY = [
        'VND',
        'USD',
        'SGD',
        'MYR',
    ];
    protected $table = 'payment_transactions';
    protected $fillable = [
        'user_id',
        'plan_id',
        'payment_method_id',
        'amount',
        'currency',
        'transaction_id',
        'status',
        'payment_date',
        'employee_id',
        'active_plan_date',
        'charge_id',
        'note',
        'system_note',
    ];
    public function plan()
    {
        return $this->belongsTo('App\Model\Plan');
    }

    public function charge()
    {
        return $this->belongsTo('App\Model\Charge');
    }

    public function payment_method()
    {
        return $this->belongsTo('App\Model\PaymentMethod','payment_method_id');
    }    
    
    public function admin()
    {
        return $this->belongsTo('App\Model\Admin');
    }

}
