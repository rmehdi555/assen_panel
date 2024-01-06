<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicesOthercosts extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type_id',
        'amount',
        'OtherCostPrice',
        'comment',
        'invoice_id',
        'exchange_id',
        'level',
    ];

    public function othercostType(): BelongsTo
    {
        return $this->belongsTo(OthercostType::class, 'type_id');
    }

    public function exchange(): BelongsTo
    {
        return $this->belongsTo(Exchanges::class, 'exchange_id');
    }
}
