<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserNotification extends Notification
{
    use Queueable;

    public $name;
    public $id;

    public function __construct($name, $id)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'A new user has registered: ' . $this->name,
            'url' => url('/users/'. $this->id),
        ];
    }
}