<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PaymentStatusMail;
use App\Models\Payment;
use App\Models\Booking;
use App\Notifications\BookingConfirmed;
use App\Notifications\PaymentSuccessful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Event;
use App\Models\User;

class PaymentVerificationController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::user();

        $scope = $request->input('scope', 'my_events');
        $organiserId = $request->input('organiser_id');
        $eventId = $request->input('event_id');
        $year = $request->input('year');
        $paymentDate = $request->input('payment_date');

        $paymentsQuery = Payment::with(['booking.event.organiser', 'booking.user', 'user']);

        if ($scope === 'my_events') {
            $paymentsQuery->whereHas('booking.event', function ($query) use ($admin) {
                $query->where('organiser_id', $admin->id);
            });
        } elseif ($scope === 'organiser_events' && $organiserId) {
            $paymentsQuery->whereHas('booking.event', function ($query) use ($organiserId) {
                $query->where('organiser_id', $organiserId);
            });
        }

        if ($eventId) {
            $paymentsQuery->whereHas('booking.event', function ($query) use ($eventId) {
                $query->where('id', $eventId);
            });
        }

        if ($paymentDate) {
            $paymentsQuery->whereDate(DB::raw('COALESCE(payment_date, created_at)'), $paymentDate);
        }

        if ($year) {
            $paymentsQuery->whereYear(DB::raw('COALESCE(payment_date, created_at)'), $year);
        }

        $pendingCount = (clone $paymentsQuery)
            ->where('status', 'pending')
            ->count();

        $analytics = [
            'total_revenue' => (clone $paymentsQuery)->sum('amount'),
            'confirmed_payments' => (clone $paymentsQuery)->where('status', 'success')->count(),
            'rejected_payments' => (clone $paymentsQuery)->where('status', 'failed')->count(),
            'not_received_payments' => (clone $paymentsQuery)->where('status', 'pending')->count(),
        ];

        $pendingPayments = (clone $paymentsQuery)
            ->orderByRaw('COALESCE(payment_date, created_at) DESC')
            ->paginate(20)
            ->withQueryString();

        $organisers = User::whereHas('events')
            ->orderBy('name')
            ->get(['id', 'name']);

        $eventsQuery = Event::with('organiser:id,name')
            ->select('id', 'title', 'organiser_id');

        if ($scope === 'my_events') {
            $eventsQuery->where('organiser_id', $admin->id);
        } elseif ($scope === 'organiser_events' && $organiserId) {
            $eventsQuery->where('organiser_id', $organiserId);
        }

        $events = $eventsQuery
            ->orderBy('title')
            ->get();

        $yearOptions = range((int) now()->format('Y'), (int) now()->subYears(10)->format('Y'));

        return view('admin.payments.pending', compact(
            'pendingPayments',
            'pendingCount',
            'analytics',
            'scope',
            'organiserId',
            'eventId',
            'year',
            'paymentDate',
            'organisers',
            'events',
            'yearOptions'
        ));
    }

    public function approve(Payment $payment)
    {
        if ($payment->status === 'success') {
            return redirect()->back()->with('success', 'Payment is already confirmed.');
        }

        // Confirm payment
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

        return redirect()->back()->with('success', 'Payment verified and booking confirmed! User has been notified.');
    }

    public function reject(Request $request, Payment $payment)
    {
        if ($payment->status === 'failed') {
            return redirect()->back()->with('success', 'Payment is already rejected.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        // Reject payment
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

        // Send payment rejected email to user
        $user = $booking->user;
        Mail::to($user->email)->send(new PaymentStatusMail($booking, $payment, 'rejected', $request->reason));

        return redirect()->back()->with('success', 'Payment rejected and booking cancelled.');
    }

    public function markNotReceived(Payment $payment)
    {
        if ($payment->status === 'pending') {
            return redirect()->back()->with('success', 'Payment is already marked as not received.');
        }

        // Mark payment as pending (not received)
        $payment->update([
            'status' => 'pending'
        ]);

        $booking = $payment->booking;
        $booking->update([
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        // Keep seats reserved while payment is unresolved.
        $booking->seats()->update(['status' => 'reserved']);

        // Send payment not received email to user
        $user = $booking->user;
        Mail::to($user->email)->send(new PaymentStatusMail($booking, $payment, 'not_received'));

        return redirect()->back()->with('success', 'Payment marked as not received.');
    }
}
