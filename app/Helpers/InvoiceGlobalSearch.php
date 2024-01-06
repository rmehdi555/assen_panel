<?php

namespace App\Helpers;

trait InvoiceGlobalSearch
{
    public static function getGloballySearchableAttributes(): array
    {
        return ['code'];
    }

    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'کد رهگیری' => $record->code,
            'نام مشتری' => $record->user->name,
        ];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return self::getUrl();
    }
}
