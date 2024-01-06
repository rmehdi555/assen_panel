<?php

namespace App\Services\RahyabSms\Log;

use App\Models\SmsLog;

class SmsLogging
{
    /**
     * This method used for log the messages to the database if "RAHYAB_SMS_ENABLE_LOGS" set to true.
     *
     * @param string $number
     * @param string $message
     * @param string $status
     * @param string|Null $recId (optional)
     * @param String|Null $error (optional)
     */
    public static function loggingInDB($number, string $message, $status, $recId = null, string $error = null)
    {
        if (!config('services.rahyab_sms.logging')) return;

        SmsLog::create([
            'provider' => 'rahyab_payam_gostaran',
            'shortcode' => env('RAHYAB_SMS_SHORTCODE'),
            'to' => $number,
            'message' => $message,
            'recId' => $recId,
            'status' => $status,
            'error' => $error,
        ]);
    }
}
