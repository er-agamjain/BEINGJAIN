<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Event Ticket - ' . $this->booking->event->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket',
            with: [
                'booking' => $this->booking,
                'event' => $this->booking->event,
                'ticket' => $this->booking->ticket,
                'user' => $this->booking->user,
            ],
        );
    }

    public function attachments(): array
    {
        // Attach PDF ticket
        return [
            // 'path' => 'storage/tickets/' . $this->booking->booking_reference . '.pdf',
        ];
    }
}
