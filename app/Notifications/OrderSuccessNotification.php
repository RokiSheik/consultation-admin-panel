<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderSuccessNotification extends Notification
{
    use Queueable;

    protected Order $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Your Order Was Successful')
                    ->greeting('Hello ' . ($notifiable->name ?? 'Customer') . ',')
                    ->line('Your order for "' . $this->order->service_name . '" has been completed successfully.')
                    ->line('Transaction ID: ' . $this->order->transaction_id)
                    ->line('Total Amount: ' . $this->order->total_amount)
                    ->line('Thank you for choosing our service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'service_name' => $this->order->service_name,
            'transaction_id' => $this->order->transaction_id,
            'total_amount' => $this->order->total_amount,
            'status' => 'completed',
        ];
    }
}
