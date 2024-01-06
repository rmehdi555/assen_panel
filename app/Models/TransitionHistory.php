<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransitionHistory extends Model
{
    use SoftDeletes;

    protected $table = 'transition_histories';

    protected $fillable = [
        'last_buy_date',
        'transition_date',
        'transition_reason',
        'transition_action',
        'transition_agent_id',
        'logistic_id',
    ];

}
