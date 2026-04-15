<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Booking Confirmed - ' . $this->booking->event->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your booking has been confirmed.')
            ->line('**Booking Reference:** ' . $this->booking->booking_reference)
            ->line('**Event:** ' . $this->booking->event->name)
            ->line('**Date:** ' . $this->booking->event->event_date->format('M d, Y'))
            ->line('**Time:** ' . \Carbon\Carbon::parse($this->booking->event->event_time)->format('h:i A'))
            ->line('**Location:** ' . $this->booking->event->location)
            ->line('**Ticket:** ' . $this->booking->ticket->name . ' x ' . $this->booking->quantity)
            ->line('**Total:** ₹' . number_format($this->booking->total_price, 2))
            ->action('View Ticket', route('user.bookings.show', $this->booking))
            ->line('Please arrive 15 minutes before the event starts.')
            ->line('Thank you for booking with us!');
    }

    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_reference' => $this->booking->booking_reference,
            'event_name' => $this->booking->event->name,
            'event_date' => $this->booking->event->event_date->format('M d, Y'),
            'total_price' => $this->booking->total_price,
            'message' => 'Your booking for ' . $this->booking->event->name . ' has been confirmed.'
        ];
    }
}
