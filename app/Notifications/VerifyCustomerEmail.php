<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyCustomerEmail extends Notification
{
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url("https://passionateworlduniversity.com/customer/verify/{$this->token}?email={$this->email}");

        return (new MailMessage)
                    ->subject('Verify Your Email')
                    ->line('Click the button below to verify your email address.')
                    ->action('Verify Email', $url)
                    ->line('If you did not create an account, no further action is required.');
    }
}
