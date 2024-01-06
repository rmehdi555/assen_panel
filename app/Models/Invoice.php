<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'isonesteppayment',
        'othercosts',
        'status',
        'finalTotaltransportprice',
        'finalTotalitemprice',
        'finalOthercosts',
        'finallTotalTax',
        'submitdate',
        'totaltransportprice',
        'totalitemprice',
        'totalTax',
        'agent_id',
        'user_id',
        'orderlevel',
        'DeliveredToCustomer',
        'cancel_description',
        'submit_factor'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public function invoiceOthercosts(): HasMany
    {
        return $this->hasMany(InvoicesOthercosts::class, 'invoice_id', 'id');
    }

    public function usersAddress(): HasOne
    {
        return $this->hasOne(UserAddress::class, 'id', 'address_id');
    }

    public function logistics(): HasMany
    {
        return $this->hasMany(Logistic::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'invoice_id', 'id');
    }

    public function latestTransaction(): HasOne
    {
        return $this->hasOne(Transaction::class, 'invoice_id', 'id')->latest();
    }

    public function boughtInvoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id')
            ->where('isbuy', true)
            ->where('isapproved', true)
            ->where('ischecked', true);
    }

    public function exchangeName()
    {
        return $this->invoiceItems()->first()->exchange->name;
    }

    public function sumPay(): float
    {
        return Transaction::where('invoice_id', $this->id)
            ->where('issuccess', 1)->sum('amount');
    }

}
