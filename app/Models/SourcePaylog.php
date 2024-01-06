<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SourcePaylog extends Model
{
    use SoftDeletes;

    protected $table = 'source_paylogs';

    protected $fillable = [
        'amount',
        'exchange_value',
        'rial_value',
        'log_date',
        'comment',
        'source_id',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
}
