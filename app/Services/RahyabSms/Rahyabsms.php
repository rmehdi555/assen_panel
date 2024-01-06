<?php

namespace App\Services\RahyabSms;

use Illuminate\Support\Facades\Facade;

/**
 * Class Rahyabsms
 * api document: https://smsonline.ir/files/WebService-Send.pdf
 *
 * @method static string send(string $mobile, string $message, string $recId = null)
 * @method static string sendAll(array $mobiles, string $message, string $recId = null)
 * @method static string getCredit()
 * @method static string GetExpireDate()
 *
 */
class Rahyabsms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Rahyabsms';
    }
}
