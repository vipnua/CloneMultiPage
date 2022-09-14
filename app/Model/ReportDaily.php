<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ReportDaily extends Model
{
    protected $table = 'report_dailies';
    protected $fillable = [
        'unique_key',
        'user_id',
        'date',
        'new_user',
        'action_user',
        'total_new_browser',
        'total_active_browser',
    ];
}
