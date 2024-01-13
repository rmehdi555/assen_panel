<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketLog extends Model
{
    use SoftDeletes;

    protected $table = 'ticket_logs';

    protected $fillable = [
        'ticket_id',
        'content',
        'status_id',
        'ip',
        'agent_id',
        'file_id',
        'department_id',
        'created_at',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class,'status_id');
    }
}
