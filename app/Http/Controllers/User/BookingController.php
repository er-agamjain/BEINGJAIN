<?php

namespace App\Http\Controllers\User;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Seat;
use App\Models\SeatCategory;
use App\Models\ShowTiming;
use App\Notifications\BookingConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function getSeats(ShowTiming $showTiming)
    {
        $showTiming->loadMissing(['venue.seatCategories', 'tickets']);

        $totalSeats = (int) ($showTiming->available_seats ?? 0);
        $tickets = $showTiming->tickets()
            ->where('quantity', '>', 0)
            ->orderBy('id')
            ->get();

        $ticketRanges = [];
        $nextSeatNumber = 1;

        foreach ($tickets as $ticket) {
            if ($nextSeatNumber > $totalSeats) {
                break;
            }

            $allocatable = min((int) $ticket->quantity, $totalSeats - $nextSeatNumber + 1);
            if ($allocatable <= 0) {
                continue;
            }

            $from = $nextSeatNumber;
            $to = $nextSeatNumber + $allocatable - 1;

            $ticketRanges[] = [
                'ticket_id' => $ticket->id,
                'name' => $ticket->name,
                'price' => (float) $ticket->price,
                'from' => $from,
                'to' => $to,
                'quantity' => $allocatable,
            ];

            $nextSeatNumber = $to + 1;
        }

        $bookingAllowed = !empty($ticketRanges);
        $allocatableSeats = $bookingAllowed ? ($nextSeatNumber - 1) : 0;
        $fallbackSeatCategoryId = optional($showTiming->venue->seatCategories->first())->id;
        $ticketSeatCategoryIds = [];

        // New venues may not have legacy seat categories yet.
        // Auto-create a default one so dynamic show-timing seat mapping can always work.
        if (!$fallbackSeatCategoryId) {
            $defaultBasePrice = (float) ($tickets->first()->price ?? 0);

            $defaultCategory = SeatCategory::firstOrCreate(
                [
                    'venue_id' => $showTiming->venue->id,
                    'name' => 'General',
                ],
                [
                    'total_seats' => max(1, $totalSeats),
                    'base_price' => $defaultBasePrice,
                    'color' => '#10B981',
                    'description' => 'Auto-created for show timing seat allocation',
                ]
            );

            if ((int) $defaultCategory->total_seats < $totalSeats) {
                $defaultCategory->update(['total_seats' => $totalSeats]);
            }

            $fallbackSeatCategoryId = $defaultCategory->id;
        }

        $palette = ['#F59E0B', '#06B6D4', '#8B5CF6', '#EF4444', '#22C55E', '#F97316'];
        foreach ($ticketRanges as $index => $range) {
            $category = SeatCategory::firstOrCreate(
                [
                    'venue_id' => $showTiming->venue->id,
                    'name' => $range['name'],
                ],
                [
                    'total_seats' => max(1, (int) $range['quantity']),
                    'base_price' => (float) $range['price'],
                    'color' => $palette[$index % count($palette)],
                    'description' => 'Auto-created from ticket category allocation',
                ]
            );

            $updatePayload = [];
            if ((float) $category->base_price !== (float) $range['price']) {
                $updatePayload['base_price'] = (float) $range['price'];
            }
            if ((int) $category->total_seats < (int) $range['quantity']) {
                $updatePayload['total_seats'] = (int) $range['quantity'];
            }
            if (!empty($updatePayload)) {
                $category->update($updatePayload);
            }

            $ticketSeatCategoryIds[$range['ticket_id']] = $category->id;
        }

        $responseSeats = [];

        DB::transaction(function () use ($showTiming, $totalSeats, $ticketRanges, $allocatableSeats, $fallbackSeatCategoryId, $ticketSeatCategoryIds, &$responseSeats) {
            $existingSeats = $showTiming->seats()->get()->keyBy('seat_number');
            $maxColumnsPerRow = 12;
            $rangeIndex = 0;

            for ($seatNumber = 1; $seatNumber <= $totalSeats; $seatNumber++) {
                $seatCode = 'S' . str_pad((string) $seatNumber, 4, '0', STR_PAD_LEFT);
                $rowNumber = (int) floor(($seatNumber - 1) / $maxColumnsPerRow) + 1;
                $columnNumber = (($seatNumber - 1) % $maxColumnsPerRow) + 1;

                while (
                    isset($ticketRanges[$rangeIndex])
                    && $seatNumber > $ticketRanges[$rangeIndex]['to']
                ) {
                    $rangeIndex++;
                }

                $range = $ticketRanges[$rangeIndex] ?? null;
                $isTicketSeat = $range && $seatNumber >= $range['from'] && $seatNumber <= $range['to'];

                $assignedPrice = $isTicketSeat ? (float) $range['price'] : null;
                $assignedTicketName = $isTicketSeat ? $range['name'] : null;
                $assignedSeatCategoryId = $isTicketSeat
                    ? ($ticketSeatCategoryIds[$range['ticket_id']] ?? $fallbackSeatCategoryId)
                    : $fallbackSeatCategoryId;
                $targetStatus = $seatNumber <= $allocatableSeats ? 'available' : 'booked';

                $seat = $existingSeats->get($seatCode);
                if (!$seat) {
                    if (!$assignedSeatCategoryId) {
                        continue;
                    }

                    $seat = Seat::create([
                        'seat_category_id' => $assignedSeatCategoryId,
                        'show_timing_id' => $showTiming->id,
                        'seat_number' => $seatCode,
                        'row_number' => $rowNumber,
                        'column_number' => $columnNumber,
                        'status' => $targetStatus,
                        'current_price' => $assignedPrice,
                    ]);
                } else {
                    $isLockedStatus = in_array($seat->status, ['booked', 'reserved', 'blocked'], true);

                    $seat->seat_category_id = $assignedSeatCategoryId;
                    $seat->row_number = $rowNumber;
                    $seat->column_number = $columnNumber;
                    $seat->current_price = $assignedPrice;

                    if (!$isLockedStatus) {
                        $seat->status = $targetStatus;
                    }

                    if ($seat->isDirty()) {
                        $seat->save();
                    }
                }

                $responseSeats[] = [
                    'id' => $seat->id,
                    'seat_number' => $seat->seat_number,
                    'row' => $seat->row_number,
                    'column' => $seat->column_number,
                    'status' => $seat->status,
                    'price' => (float) ($seat->current_price ?? 0),
                    'ticket_name' => $assignedTicketName,
                ];
            }
        });

        return response()->json([
            'seats' => $responseSeats,
            'ticket_ranges' => $ticketRanges,
            'total_seats' => $totalSeats,
            'allocatable_seats' => $allocatableSeats,
            'booking_allowed' => $bookingAllowed,
            'message' => $bookingAllowed
                ? null
                : 'Currently booking not allowed: no categorized tickets available for this show timing.',
            'venue' => [
                'id' => $showTiming->venue->id,
                'name' => $showTiming->venue->name,
            ]
        ]);
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'show_timing_id' => 'required|exists:show_timings,id',
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        $showTiming = ShowTiming::findOrFail($validated['show_timing_id']);
        $seatIds = $validated['seat_ids'];
        $quantity = count($seatIds);

        // Get the selected seats with their categories
        $seats = Seat::whereIn('id', $seatIds)
            ->where('show_timing_id', $showTiming->id)
            ->where('status', 'available')
            ->with('seatCategory')
            ->get();

        // Verify all seats belong to the same show timing
        if ($seats->count() !== $quantity) {
            return redirect()->back()->with('error', 'Some seats are no longer available.');
        }

        // Calculate total price based on selected seats
        $totalPrice = $seats->sum(function($seat) {
            return $seat->current_price ?? $seat->seatCategory->base_price;
        });

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'show_timing_id' => $showTiming->id,
            'ticket_id' => null,
            'quantity' => $quantity,
            'total_price' => $totalPrice,
            'booking_reference' => 'BK' . strtoupper(uniqid()),
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Attach seats to booking and mark as reserved
        $booking->seats()->attach($seatIds);
        $seats->each(function($seat) {
            $seat->update(['status' => 'reserved']);
        });

        // Redirect to payment for tickets
        return redirect()->route('user.payments.create', $booking)->with('success', 'Seats reserved! Proceed to payment.');
    }

    public function history()
    {
        $bookings = Auth::user()->bookings()->with('event', 'showTiming')->latest()->paginate(15);
        return view('user.bookings.history', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.bookings.show', compact('booking'));
    }
}
