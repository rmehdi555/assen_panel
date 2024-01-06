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
        'attachment_id',
        'department_id',
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }
}
