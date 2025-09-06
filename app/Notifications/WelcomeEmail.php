<?php


namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeEmail extends Notification
{
    protected $customerName;

    public function __construct($customerName)
    {
        $this->customerName = $customerName;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to Passionate World University!')
            ->greeting('Hello ' . $this->customerName . ',')
            ->line('Thank you for registering with us. We’re excited to have you on board!')
            ->line('If you have any questions, feel free to reply to this email.')
            ->salutation('Best regards, Passionate World University Team');
    }
}

