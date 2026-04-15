<?php

namespace App\Http\Controllers\Organiser;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::whereHas('event', function ($q) {
            $q->where('organiser_id', Auth::id());
        });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->with('user', 'event', 'ticket')->paginate(20);

        return view('organiser.bookings.index', compact('bookings'));
    }

    public function history()
    {
        $bookings = Booking::whereHas('event', function ($q) {
            $q->where('organiser_id', Auth::id());
        })->with('user', 'event')->latest()->paginate(20);

        return view('organiser.bookings.history', compact('bookings'));
    }
}
