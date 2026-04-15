<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Notifications\EventReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send {--hours=24 : Hours before event to send reminder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send event reminders to users for upcoming bookings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $targetTime = now()->addHours($hours);

        $this->info("Looking for events starting around " . $targetTime->format('Y-m-d H:i'));

        // Find bookings for events happening in approximately X hours
        $bookings = Booking::where('status', 'confirmed')
            ->where('payment_status', 'paid')
            ->with(['user', 'event'])
            ->whereHas('event', function ($query) use ($targetTime, $hours) {
                $query->whereBetween('event_date', [
                    $targetTime->copy()->subHour(),
                    $targetTime->copy()->addHour()
                ]);
            })
            ->get();

        $count = 0;
        foreach ($bookings as $booking) {
            $eventDateTime = Carbon::parse(
                $booking->event->event_date->format('Y-m-d') . ' ' . $booking->event->event_time
            );

            // Only send if we haven't already sent a reminder for this booking
            // (You can add a 'reminder_sent_at' column to bookings table to track this)
            if ($eventDateTime->isFuture()) {
                $booking->user->notify(new EventReminder($booking, $hours));
                $count++;
                $this->info("Sent reminder to {$booking->user->email} for {$booking->event->name}");
            }
        }

        $this->info("Sent {$count} event reminders.");
        return Command::SUCCESS;
    }
}
