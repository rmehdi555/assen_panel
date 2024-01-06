<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalculatorConfig extends Model
{
    use SoftDeletes;

    protected $table = 'calculatorconfigs';

    protected $fillable = [
        'region_id',
        'BuyBroker',
        'MinValueForWeight',
        'DividerFromExchange',
        'IndexExchange',
        'EachKgValue',
        'AdditionalPerRow',
        'updated_by',
    ];

    public function regions(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
}
