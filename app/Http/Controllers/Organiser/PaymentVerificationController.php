<?php

namespace App\Http\Controllers\Organiser;

use App\Http\Controllers\Controller;
use App\Mail\PaymentStatusMail;
use App\Models\Payment;
use App\Models\Booking;
use App\Notifications\BookingConfirmed;
use App\Notifications\PaymentSuccessful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentVerificationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display pending payments for organizer's events
     */
    public function index()
    {
        $organizer = Auth::user();
        
        // Get all pending payments for this organizer's events
        $pendingPayments = Payment::with(['booking.event', 'booking.user', 'user'])
            ->whereHas('booking.event', function ($query) use ($organizer) {
                $query->where('organiser_id', $organizer->id);
            })
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('organiser.payments.pending', compact('pendingPayments'));
    }

    /**
     * Display payment history for organizer's events
     */
    public function history()
    {
        $organizer = Auth::user();
        
        // Get all payments (not just pending) for this organizer's events
        $payments = Payment::with(['booking.event', 'booking.user', 'user'])
            ->whereHas('booking.event', function ($query) use ($organizer) {
                $query->where('organiser_id', $organizer->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('organiser.payments.history', compact('payments'));
    }

    /**
     * Approve/confirm a payment
     */
    public function approve(Payment $payment)
    {
        // Authorization: check if this payment belongs to organizer's event
        $this->authorize('approvePayment', $payment);

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

        // Send payment confirmed email to user
        $user = $booking->user;
        Mail::to($user->email)->send(new PaymentStatusMail($booking, $payment, 'confirmed'));

        return redirect()->back()->with('success', 'Payment confirmed! User has been notified.');
    }

    /**
     * Reject a payment
     */
    public function reject(Request $request, Payment $payment)
    {
        // Authorization: check if this payment belongs to organizer's event
        $this->authorize('rejectPayment', $payment);

        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Payment has already been processed.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        // Update payment status
        $payment->update([
            'status' => 'failed',
            'notes' => $request->reason
        ]);

        // Update booking status
        $booking = $payment->booking;
        $booking->update([
            'payment_status' => 'failed',
            'status' => 'cancelled',
        ]);

        // Release seats
        $booking->seats()->update(['status' => 'available']);

        // Send payment rejected email to user
        $user = $booking->user;
        Mail::to($user->email)->send(new PaymentStatusMail($booking, $payment, 'rejected', $request->reason));

        return redirect()->back()->with('success', 'Payment rejected and booking cancelled.');
    }

    /**
     * Mark payment as not received yet (revert to pending or keep pending)
     */
    public function markNotReceived(Payment $payment)
    {
        // Authorization: check if this payment belongs to organizer's event
        $this->authorize('updatePayment', $payment);

        // Keep status as pending or update notes
        $payment->update([
            'notes' => 'Marked as not received by organizer - ' . now()->format('M d, Y h:i A')
        ]);

        // Send payment not received email to user
        $booking = $payment->booking;
        $user = $booking->user;
        Mail::to($user->email)->send(new PaymentStatusMail($booking, $payment, 'not_received'));

        return redirect()->back()->with('success', 'Payment marked as not received yet.');
    }

    /**
     * Update payment status via form (for admin/organizer dashboard)
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        // Authorization: check if this payment belongs to organizer's event
        $this->authorize('updatePayment', $payment);

        $request->validate([
            'status' => 'required|in:pending,success,failed',
            'notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $payment->status;
        $newStatus = $request->status;

        // Only allow certain transitions
        if ($oldStatus !== 'pending' && $newStatus !== $oldStatus) {
            return redirect()->back()->with('error', 'Cannot change status of already processed payments.');
        }

        // Handle different status updates
        if ($newStatus === 'success' && $oldStatus !== 'success') {
            // Approve payment
            $payment->update(['status' => 'success']);
            $booking = $payment->booking;
            $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);
            $booking->seats()->update(['status' => 'booked']);
            
            // Send payment confirmed email
            $user = $booking->user;
            Mail::to($user->email)->send(new PaymentStatusMail($booking, $payment, 'confirmed'));
            
            return redirect()->back()->with('success', 'Payment confirmed!');
        } 
        elseif ($newStatus === 'failed' && $oldStatus !== 'failed') {
            // Reject payment
            $payment->update(['status' => 'failed', 'notes' => $request->notes]);
            $booking = $payment->booking;
            $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
            $booking->seats()->update(['status' => 'available']);

            // Send payment rejected email
            $user = $booking->user;
            Mail::to($user->email)->send(new PaymentStatusMail($booking, $payment, 'rejected', $request->notes));
            
            return redirect()->back()->with('success', 'Payment rejected!');
        }
        elseif ($newStatus === 'pending') {
            // Mark as pending/not received
            $payment->update(['status' => 'pending', 'notes' => $request->notes]);

            // Send payment not received email
            $booking = $payment->booking;
            $user = $booking->user;
            Mail::to($user->email)->send(new PaymentStatusMail($booking, $payment, 'not_received'));

            return redirect()->back()->with('success', 'Payment status updated to pending.');
        }

        return redirect()->back();
    }
}
