<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use App\Notifications\BookingConfirmed;
use App\Notifications\PaymentSuccessful;
use Illuminate\Http\Request;

class PaymentVerificationController extends Controller
{
    public function index()
    {
        $pendingPayments = Payment::with(['booking.event', 'booking.user', 'user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.payments.pending', compact('pendingPayments'));
    }

    public function approve(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Payment has already been processed.');
        }

        // Update payment status
        $payment->update([
            'status' => 'success'
        ]);

        // Update booking status
        $booking = $payment->booking;
        $booking->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        // Mark seats as booked
        $booking->seats()->update(['status' => 'booked']);

        // Send notifications
        $user = $booking->user;
        $user->notify(new BookingConfirmed($booking));
        $user->notify(new PaymentSuccessful($booking, $payment));

        return redirect()->back()->with('success', 'Payment verified and booking confirmed! User has been notified.');
    }

    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Payment has already been processed.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        // Update payment status
        $payment->update([
            'status' => 'failed'
        ]);

        // Update booking status
        $booking = $payment->booking;
        $booking->update([
            'payment_status' => 'failed',
            'status' => 'cancelled',
        ]);

        // Release seats
        $booking->seats()->update(['status' => 'available']);

        // You could send a notification to user about rejection here
        // $user->notify(new PaymentRejected($booking, $payment, $request->reason));

        return redirect()->back()->with('success', 'Payment rejected and booking cancelled.');
    }
}
