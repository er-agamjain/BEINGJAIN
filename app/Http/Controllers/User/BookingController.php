<?php

namespace App\Http\Controllers\User;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Seat;
use App\Models\ShowTiming;
use App\Notifications\BookingConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function getSeats(ShowTiming $showTiming)
    {
        // Get seats with status so frontend can block booked/reserved
        $seats = $showTiming->seats()
            ->with('seatCategory')
            ->get()
            ->groupBy(function($seat) {
                return $seat->seatCategory->id;
            })
            ->map(function($groupedSeats, $categoryId) {
                $category = $groupedSeats->first()->seatCategory;
                return [
                    'category_id' => $categoryId,
                    'category_name' => $category->name,
                    'category_color' => $category->color,
                    'price' => $category->base_price,
                    'seats' => $groupedSeats->map(function($seat) {
                        return [
                            'id' => $seat->id,
                            'seat_number' => $seat->seat_number,
                            'row' => $seat->row_number,
                            'column' => $seat->column_number,
                            'status' => $seat->status,
                            'price' => $seat->current_price ?? $seat->seatCategory->base_price,
                        ];
                    })->values()
                ];
            })
            ->values();

        $seatCategories = $showTiming->venue->seatCategories;

        return response()->json([
            'seats' => $seats,
            'categories' => $seatCategories,
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
