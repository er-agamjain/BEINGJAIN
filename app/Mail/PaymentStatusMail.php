<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $statusType; // confirmed, rejected, not_received

    public function __construct(
        public Booking $booking,
        public Payment $payment,
        string $statusType,
        public ?string $reason = null
    ) {
        $this->statusType = $statusType;
        $this->booking->loadMissing(['event', 'user', 'seats', 'showTiming']);
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'confirmed' => 'Payment Confirmed - ' . $this->booking->booking_reference,
            'rejected' => 'Payment Rejected - ' . $this->booking->booking_reference,
            'not_received' => 'Payment Not Received - ' . $this->booking->booking_reference,
        ];

        return new Envelope(
            subject: $subjects[$this->statusType] ?? 'Payment Update - ' . $this->booking->booking_reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-status',
            with: [
                'booking' => $this->booking,
                'payment' => $this->payment,
                'event' => $this->booking->event,
                'user' => $this->booking->user,
                'statusType' => $this->statusType,
                'reason' => $this->reason,
            ],
        );
    }
}
