<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketStatus extends Model
{
    use SoftDeletes;
    protected $table = 'ticket_status';

    protected $fillable = [
        'name'
    ];
}
