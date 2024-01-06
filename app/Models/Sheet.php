<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sheet extends Model
{
    use SoftDeletes;
    protected $table = 'sheets';

    protected $fillable = [
        'name',
        'ratio',

        'cost_transfer_usa_to_aue',
        'exchange_transfer_usa_to_aue',
        'exchange_id_transfer_usa_to_aue',

        'cost_delivery_to_aue',
        'exchange_delivery_to_aue',
        'exchange_id_delivery_to_aue',

        'cost_office_aue',
        'exchange_office_aue',
        'exchange_id_office_aue',

        'cost_iran_customs',
        'cost_transfer_to_tehran',
        'cost_store',
        'cost_store_to_office',
        'cost_post',
    ];

    public function exchangeIdTransfer(): BelongsTo
    {
        return $this->belongsTo(Exchanges::class, 'exchange_id_transfer_usa_to_aue');
    }


}
