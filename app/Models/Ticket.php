<?php

namespace App\Models;

use App\Exceptions\Handler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use mysql_xdevapi\Collection;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'status_id',
        'invoice_id',
        'user_id',
        'department_id',
        'code'
    ];

    public function ticketStatus(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class,'status_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function logs()
    {
        return $this->hasMany(TicketLog::class, 'ticket_id', 'id');
    }
}
