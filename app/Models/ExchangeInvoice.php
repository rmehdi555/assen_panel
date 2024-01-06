<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExchangeInvoice extends Model
{
    use SoftDeletes;

    protected $table = 'exchanges_invoices';

    protected $fillable = [
        'value',
        'invoice_id',
        'exchange_id',
    ];

    /**
     * Update or create all exchange value for invoice.
     *
     * @param int $invoiceId
     * @return void
     */
    public static function storeAllExchangeValueForInvoice(int $invoiceId): void
    {
        $exchanges = Exchanges::all();

        foreach ($exchanges as $exchange)
        {
            self::updateOrCreate([
                'exchange_id' => $exchange->id,
                'invoice_id'  => $invoiceId,
            ],[
                'value'       => $exchange->value,
            ]);
        }
    }

}
