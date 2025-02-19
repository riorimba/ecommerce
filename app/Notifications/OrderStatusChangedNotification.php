<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    protected $order_id;
    protected $status;
    protected $orderId;
    /**
     * Create a new notification instance.
     */
    public function __construct($order_id, $orderId, $status)
    {
        $this->order_id = $order_id;
        $this->orderId = $orderId;
        $this->status = $status;
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
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Order ID: ' . $this->order_id . ' status has been changed to ' . $this->status,
            'url' => url('/orders/' . $this->orderId),
        ];
    }
}
