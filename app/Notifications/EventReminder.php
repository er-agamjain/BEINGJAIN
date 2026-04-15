<?php

namespace App\Notifications;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;
    protected $hoursBeforeEvent;

    public function __construct(Booking $booking, int $hoursBeforeEvent = 24)
    {
        $this->booking = $booking;
        $this->hoursBeforeEvent = $hoursBeforeEvent;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $eventDateTime = Carbon::parse($this->booking->event->event_date->format('Y-m-d') . ' ' . $this->booking->event->event_time);
        
        return (new MailMessage)
            ->subject('Reminder: ' . $this->booking->event->name . ' in ' . $this->hoursBeforeEvent . ' hours')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a friendly reminder about your upcoming event.')
            ->line('**Event:** ' . $this->booking->event->name)
            ->line('**Date & Time:** ' . $eventDateTime->format('M d, Y h:i A'))
            ->line('**Location:** ' . $this->booking->event->location)
            ->line('**Your Seats:** ' . $this->booking->ticket->name . ' x ' . $this->booking->quantity)
            ->line('**Booking Reference:** ' . $this->booking->booking_reference)
            ->action('View Ticket', route('user.tickets.view', $this->booking))
            ->line('Please arrive at least 15 minutes before the event starts.')
            ->line('We look forward to seeing you!');
    }

    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_reference' => $this->booking->booking_reference,
            'event_name' => $this->booking->event->name,
            'event_date' => $this->booking->event->event_date->format('M d, Y'),
            'hours_before' => $this->hoursBeforeEvent,
            'message' => 'Reminder: ' . $this->booking->event->name . ' starts in ' . $this->hoursBeforeEvent . ' hours'
        ];
    }
}
