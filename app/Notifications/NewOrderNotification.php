<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order_id;
    protected $total;
    protected $orderId;

    /**
     * Create a new notification instance.
     */
    public function __construct($order_id, $orderId, $total)
    {
        $this->order_id = $order_id;
        $this->orderId = $orderId;
        $this->total = $total;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'A new order has been placed. Order ID: ' . $this->order_id . ', Total: $' . $this->total,
            'url' => url('/orders/' . $this->orderId),
        ];
    }
}
