<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceDescription extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'invoice_id',
        'invoice_item_id',
        'logistics_id',
        'description',
        'level',
        'agent_id',
    ];
}
