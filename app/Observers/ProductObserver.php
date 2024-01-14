<?php

namespace App\Observers;

use App\Models\ProductPriceLogs;
use App\Models\Products;

class ProductObserver
{
    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = true;

    /**
     * Handle the Products "created" event.
     */
    public function created(Products $products): void
    {
        //
    }

    /**
     * Handle the Products "updated" event.
     */
    public function updated(Products $product): void
    {

    }

    /**
     * Handle the Products "deleted" event.
     */
    public function deleted(Products $products): void
    {
        //
    }

    /**
     * Handle the Products "restored" event.
     */
    public function restored(Products $products): void
    {
        //
    }

    /**
     * Handle the Products "force deleted" event.
     */
    public function forceDeleted(Products $products): void
    {
        //
    }


    public function updating($product)
    {
        ProductPriceLogs::create([
            'product_id' => $product->id,
            'price' => $product->price,
        ]);
    }
}
