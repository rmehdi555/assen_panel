<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'token',
        'method',
        'amount',
        'issuccess',
        'cardNo',
        'transactioncode',
        'requesttransactioncode',
        'error',
        'comment',
        'date',
        'payment_method_id',
        'user_id',
        'invoice_id',
        'transId',
        'refnumber',
        'trackingCode',
        'CID',
        'status',
        'admin_user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
    public function scopeIssuccess(Builder $query): void
    {
        $query->where('issuccess', true);
    }
}
