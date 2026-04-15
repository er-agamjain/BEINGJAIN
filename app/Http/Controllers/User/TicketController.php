<?php

namespace App\Http\Controllers\User;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function downloadPdf(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'confirmed') {
            abort(403, 'Ticket not available');
        }

        $pdf = PDF::loadView('tickets.pdf', compact('booking'));
        
        return $pdf->download('ticket-' . $booking->booking_reference . '.pdf');
    }

    public function view(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.tickets.view', compact('booking'));
    }
}
