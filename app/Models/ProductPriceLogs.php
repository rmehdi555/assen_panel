<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPriceLogs extends Model
{

    protected $fillable = [
        'price', 'product_id'
    ];
}
