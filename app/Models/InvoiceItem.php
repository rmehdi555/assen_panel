<?php

namespace App\Models;

use App\Helpers\Convertors;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class InvoiceItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'link',
        'cost',
        'count',
        'firstweight',
        'description',
        'ischecked',
        'isauction',
        'isapproved',
        'isbuy',
        'isteammate',
        'unapprovedescription',
        'transportprice',
        'itemprice',
        'exchangevalue',
        'brokerwageprice',
        'singleitemfullprice',
        'prefactorcomment',
        'preordercomment',
        'buybroker',
        'eachkgvalue',
        'AdditionalPerRow',
        'minweightval',
        'image',
        'apistatus',
        'invoice_id',
        'exchange_id',
        'region_id',
        'breakable',
        'finalweight',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function exchange(): HasOne
    {
        return $this->hasOne(Exchanges::class, 'id', 'exchange_id');
    }

    public function logistics(): HasMany
    {
        return $this->hasMany(Logistic::class, 'invoice_item_id', 'id');
    }

    public function invoiceDescription(): HasMany
    {
        return $this->hasMany(InvoiceDescription::class, 'id', 'invoice_item_id');
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('isapproved', true);
    }

    public static function getPricesForApprovedInvoiceItem(int $invoiceId): InvoiceItem
    {
        return self::select(DB::raw('SUM(itemprice) AS totalitemprice, SUM(transportprice) AS totaltransportprice'))
            ->where('invoice_id', $invoiceId)
            ->where('isapproved', 1)
            ->first();
    }
}
