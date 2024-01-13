<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyConvert extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'exFrom', 'exTo', 'rate', 'status'
    ];

    protected $dates = ['deleted_at'];
}
