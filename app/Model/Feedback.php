<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'id',
        'uuid',
        'user_id',
        'type',
        'content',
    ];

}
