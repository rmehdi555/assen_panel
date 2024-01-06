<?php

namespace App\Notifications\Channels;

use App\Services\RahyabSms\Rahyabsms;
use Illuminate\Notifications\Notification;

class SmsRahyabChannel
{
    public function send($notifiable, Notification $notification)
    {
        $number = $notifiable->cell_number;
        $message = $notification->toRahyab('text')['text'];
        return Rahyabsms::send($number, $message);
    }
}
