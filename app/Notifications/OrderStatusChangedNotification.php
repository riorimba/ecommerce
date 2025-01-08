<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    protected $id;
    protected $order_id;
    protected $status;
    /**
     * Create a new notification instance.
     */
    public function __construct($id, $order_id, $status)
    {
        $this->id = $id;
        $this->order_id = $order_id;
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
            'id' => (string) Str::uuid(),
            'message' => 'Order ID: ' . $this->order_id . ' status has been changed to ' . $this->status,
            'url' => url('/orders/' . $this->id),
        ];
    }
}
