<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderPendingNotification extends Notification
{
    use Queueable;

    protected $order; // store the order object

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order; // assign order to property
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
            ->subject('Your Order is Pending')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your order has been placed and is currently pending.')
            ->line('Order ID: ' . $this->order->transaction_id)
            ->line('Service: ' . $this->order->service_name)
            ->line('Package: ' . $this->order->package_type)
            ->line('Amount: BDT' . $this->order->total_amount)
            ->line('We will notify you once the payment is completed.')
            ->salutation('Thank you for choosing us!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'service_name' => $this->order->service_name,
            'transaction_id' => $this->order->transaction_id,
            'total_amount' => $this->order->total_amount,
            'status' => 'pending',
        ];
    }
}
