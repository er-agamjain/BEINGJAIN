<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessful extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $payment;

    public function __construct(Booking $booking, Payment $payment)
    {
        $this->booking = $booking;
        $this->payment = $payment;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Payment Successful - ' . $this->booking->event->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your payment has been successfully processed.')
            ->line('**Booking Reference:** ' . $this->booking->booking_reference)
            ->line('**Event:** ' . $this->booking->event->name)
            ->line('**Amount Paid:** ₹' . number_format($this->payment->amount, 2))
            ->line('**Payment Method:** ' . ucfirst($this->payment->payment_method))
            ->line('**Transaction ID:** ' . ($this->payment->transaction_id ?? 'N/A'))
            ->line('**Payment Date:** ' . $this->payment->payment_date->format('M d, Y h:i A'))
            ->action('View Ticket', route('user.tickets.view', $this->booking))
            ->action('Download PDF', route('user.tickets.download', $this->booking))
            ->line('You will receive a reminder before the event.')
            ->line('Thank you for your payment!');
    }

    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'payment_id' => $this->payment->id,
            'booking_reference' => $this->booking->booking_reference,
            'amount' => $this->payment->amount,
            'payment_method' => $this->payment->payment_method,
            'message' => 'Payment of ₹' . number_format($this->payment->amount, 2) . ' received successfully for ' . $this->booking->event->name
        ];
    }
}
