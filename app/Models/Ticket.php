<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'status_id',
        'invoice_id',
        'user_id',
        'department_id',
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
        return $this->belongsTo(User::class);
    }
}
