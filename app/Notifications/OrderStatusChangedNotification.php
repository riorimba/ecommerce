<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    protected $id;
    protected $status;
    /**
     * Create a new notification instance.
     */
    public function __construct($id, $status)
    {
        $this->id = $id;
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
            'message' => 'Order ID: ' . $this->id . ' status has been changed to ' . $this->status,
            'url' => url('/orders/' . $this->id),
        ];
    }
}
