<?php

namespace App\Helpers;

trait LogisticGlobalSearch
{
    public static function getGloballySearchableAttributes(): array
    {
        return ['invoice.code'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'کد رهگیری' => $record->invoice->code,
            'نام کالا' => $record->invoiceItem->name,
        ];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return self::getUrl();
    }
}
