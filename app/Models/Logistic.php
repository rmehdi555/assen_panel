<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logistic extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status_id',
        'transportprice',
        'invoice_item_id',
        'itemprice',
        'exchangevalue',
        'exchangeRealValue',
        'brokerwageprice',
        'singleItemfullprice',
        'issensitive',
        'source_id',
        'account_id',
        'base_id',
        'orderId',
        'account_discount',
        'iscancel',
        'buyingdate',
        'orderexpireddate',
        'isdeclare',
        'transitionmode',
        'logisticstatus',
        'price',
        'agent_id',
        'exchange_id',
        'invoice_id',
        'description',
        'estimateddatetobase',
        'carrier_id',
        'tracknumber',
        'deliverydate',
        'officenumber',
        'dubaiestimatedarivedate',
        'dubaitracknumber',
        'datearrivetodubai',
        'transporter',
        'sendPackNumber',
        'estimatedDateToTehran',
        'arrivedintehran',
        'istransportedtocustomer',
        'IsDeliveredToCustomer',
        'length',
        'height',
        'width',
        'contentWeight',
        'massweight',
        'finalweight',
        'arrivedintehran',
        'finalTransportprice',
        'isrebuy',
        'isTransitionAccessToCustomer',
        'SendToCustomerTrackCode',
        'SendToCustomerCost',
        'SentToCustomerBy',
        'DeliveredToCustomer',
        'canceldate',
        'canceltype',
        'cancelreason',
        'transitionreason',
        'sheet_id'
    ];

    public function invoiceItem(): BelongsTo
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function path(): BelongsTo
    {
        return $this->belongsTo(Path::class, 'transporter');
    }

    public function invoiceDescription(): HasMany
    {
        return $this->hasMany(InvoiceDescription::class, 'logistics_id');
    }

    public function exchange(): BelongsTo
    {
        return $this->BelongsTo(Exchanges::class, 'exchange_id', 'id');
    }

    public function base(): BelongsTo
    {
        return $this->BelongsTo(Base::class, 'base_id', 'id');
    }

    public function carrier(): BelongsTo
    {
        return $this->BelongsTo(Carrier::class, 'carrier_id', 'id');
    }

    public function source(): HasOne
    {
        return $this->hasOne(Source::class, 'id', 'source_id');
    }

    public function account(): HasOne
    {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }

    public function cancelReason(): HasOne
    {
        return $this->hasOne(CancelReason::class, 'id', 'transitionreason');
    }

    public function invoiceDescriptionLevelBuyInvoice(): HasOne
    {
        return $this->hasOne(InvoiceDescription::class, 'logistics_id')->where('level', 'buy-invoice');
    }

    public function invoiceDescriptionLevelSetTrackNumber(): HasOne
    {
        return $this->hasOne(InvoiceDescription::class, 'logistics_id')->where('level', 'set-track-number');
    }

    public function invoiceDescriptionLevelSetOfficeNumber(): HasOne
    {
        return $this->hasOne(InvoiceDescription::class, 'logistics_id')->where('level', 'set-office-number');
    }

    public function invoiceDescriptionLevelSetDubaiTrackNumber(): HasOne
    {
        return $this->hasOne(InvoiceDescription::class, 'logistics_id')->where('level', 'set-dubai-track-number');
    }

    public function invoiceDescriptionLevelIsInDubai(): HasOne
    {
        return $this->hasOne(InvoiceDescription::class, 'logistics_id')->where('level', 'is-in-dubai');
    }

    public function invoiceDescriptionLevelSendToTehran(): HasOne
    {
        return $this->hasOne(InvoiceDescription::class, 'logistics_id')->where('level', 'send-to-tehran');
    }

    public function invoiceDescriptionLevelIsInTehran(): HasOne
    {
        return $this->hasOne(InvoiceDescription::class, 'logistics_id')->where('level', 'is-in-tehran');
    }


}
