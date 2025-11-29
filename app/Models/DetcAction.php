<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetcAction extends Model
{
     protected $table = 'detc_actions';

    protected $fillable = [
        'complaint_id',
        'proposed_action',
        'action_taken',
        'reason',
        'remarks',
        'detc_user_id',
        'status',
    ];
}
