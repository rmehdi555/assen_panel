<?php

namespace App\Notifications;

use App\Models\Invoice;
use App\Notifications\Channels\SmsRahyabChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendSuccessfullyBuyFromThirdShop extends Notification// implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Invoice $invoice)
    {
        $this->queue = 'sms-queue';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [SmsRahyabChannel::class];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toRahyab($notifiable)
    {
        $message = __('message.send_successfully_buy_from_third_shop', ['code' => $this->invoice->code]);
        return [
            'text' => $message
        ];
    }
}
