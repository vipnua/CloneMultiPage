<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminInfor extends Model
{
    protected $table = 'admin_infors';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'birthday',
        'address',
        'gender',
        'admin_id',
    ];

    /**
     * Get the user that owns the AdminInfor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo('App\Model\Admin', 'admin_id', 'id');
    }
}
