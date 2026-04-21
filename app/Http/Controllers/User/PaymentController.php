<?php

namespace App\Http\Controllers\User;

use App\Helpers\PaymentHelper;
use App\Mail\PaymentStatusMail;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Seat;
use App\Models\SeatCategory;
use App\Notifications\BookingConfirmed;
use App\Notifications\PaymentSuccessful;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function create(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->payment_status !== 'pending') {
            return redirect()->back()->with('info', 'Payment already processed');
        }

        $booking->loadMissing([
            'event.city',
            'showTiming.venue.city',
            'seats.seatCategory',
            'showTiming.tickets',
        ]);

        $this->syncBookingSeatCategories($booking);
        $booking->load('seats.seatCategory');

        return view('user.payments.create', compact('booking'));
    }

    private function syncBookingSeatCategories(Booking $booking): void
    {
        if (!$booking->showTiming || !$booking->showTiming->venue || !$booking->seats || $booking->seats->isEmpty()) {
            return;
        }

        $totalSeats = (int) ($booking->showTiming->available_seats ?? 0);
        if ($totalSeats <= 0) {
            return;
        }

        $tickets = $booking->showTiming->tickets()
            ->where('quantity', '>', 0)
            ->orderBy('id')
            ->get();

        if ($tickets->isEmpty()) {
            return;
        }

        $ranges = [];
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
            $ranges[] = [
                'ticket_id' => $ticket->id,
                'name' => $ticket->name,
                'price' => (float) $ticket->price,
                'from' => $from,
                'to' => $to,
                'quantity' => $allocatable,
            ];
            $nextSeatNumber = $to + 1;
        }

        if (empty($ranges)) {
            return;
        }

        $fallbackCategory = SeatCategory::firstOrCreate(
            [
                'venue_id' => $booking->showTiming->venue->id,
                'name' => 'General',
            ],
            [
                'total_seats' => max(1, $totalSeats),
                'base_price' => 0,
                'color' => '#10B981',
                'description' => 'Auto-created for show timing seat allocation',
            ]
        );

        $ticketCategoryMap = [];
        foreach ($ranges as $index => $range) {
            $palette = ['#F59E0B', '#06B6D4', '#8B5CF6', '#EF4444', '#22C55E', '#F97316'];
            $category = SeatCategory::firstOrCreate(
                [
                    'venue_id' => $booking->showTiming->venue->id,
                    'name' => $range['name'],
                ],
                [
                    'total_seats' => max(1, (int) $range['quantity']),
                    'base_price' => (float) $range['price'],
                    'color' => $palette[$index % count($palette)],
                    'description' => 'Auto-created from ticket category allocation',
                ]
            );

            $ticketCategoryMap[$range['ticket_id']] = $category->id;
        }

        foreach ($booking->seats as $seat) {
            $seatNumber = (int) preg_replace('/[^0-9]/', '', (string) $seat->seat_number);
            if ($seatNumber <= 0) {
                continue;
            }

            $matchedRange = null;
            foreach ($ranges as $range) {
                if ($seatNumber >= $range['from'] && $seatNumber <= $range['to']) {
                    $matchedRange = $range;
                    break;
                }
            }

            $targetSeatCategoryId = $matchedRange
                ? ($ticketCategoryMap[$matchedRange['ticket_id']] ?? $fallbackCategory->id)
                : $fallbackCategory->id;

            $updates = [];
            if ((int) $seat->seat_category_id !== (int) $targetSeatCategoryId) {
                $updates['seat_category_id'] = $targetSeatCategoryId;
            }
            if ($matchedRange && (float) $seat->current_price !== (float) $matchedRange['price']) {
                $updates['current_price'] = (float) $matchedRange['price'];
            }

            if (!empty($updates)) {
                $seat->update($updates);
            }
        }
    }

    public function store(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:stripe,paypal,razorpay,upi,netbanking,wallet',
        ]);

        // Redirect to payment processing page instead of immediately completing payment
        return view('user.payments.process', [
            'booking' => $booking->load(['event', 'showTiming', 'seats']),
            'paymentMethod' => $validated['payment_method']
        ]);
    }

    // New method to confirm payment after user completes it
    public function confirm(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->payment_status !== 'pending') {
            return redirect()->route('user.bookings.index')->with('info', 'Payment already processed');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:stripe,paypal,razorpay,upi,netbanking,wallet',
            'bank_code' => 'nullable|in:sbi,hdfc,icici,axis,pnb,kotak,other|required_if:payment_method,netbanking',
            'wallet_provider' => 'nullable|in:paytm,phonepe,amazonpay|required_if:payment_method,wallet',
            'wallet_redirected' => 'nullable|in:1|required_if:payment_method,wallet',
        ]);

        $paymentMethod = $validated['payment_method'];
        $bankCode = $validated['bank_code'] ?? null;
        $walletProvider = $validated['wallet_provider'] ?? null;

        // Process payment based on method
        return match ($paymentMethod) {
            'stripe' => $this->processStripePayment($booking),
            'paypal' => $this->processPayPalPayment($booking),
            'razorpay' => $this->processRazorpayPayment($booking),
            default => $this->processGenericPayment($booking, $paymentMethod, $bankCode, $walletProvider),
        };
    }

    public function createRazorpayOrder(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->payment_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Payment already processed for this booking.'
            ], 422);
        }

        $keyId = PaymentHelper::getRazorpayKeyId();
        $secretKey = PaymentHelper::getRazorpaySecretKey();

        if (empty($keyId) || empty($secretKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Razorpay is not configured. Please contact support.'
            ], 422);
        }

        $amountInPaise = (int) round(((float) $booking->total_price) * 100);

        $response = Http::withBasicAuth($keyId, $secretKey)
            ->post('https://api.razorpay.com/v1/orders', [
                'amount' => $amountInPaise,
                'currency' => 'INR',
                'receipt' => $booking->booking_reference,
                'payment_capture' => 1,
                'notes' => [
                    'booking_id' => (string) $booking->id,
                    'booking_reference' => $booking->booking_reference,
                ],
            ]);

        if (!$response->successful() || empty($response->json('id'))) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to initiate Razorpay order. Please try again.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'key' => $keyId,
            'order_id' => $response->json('id'),
            'amount' => $amountInPaise,
            'currency' => 'INR',
            'name' => config('app.name', 'Event Booking'),
            'description' => 'Booking Payment - ' . $booking->booking_reference,
            'prefill' => [
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'notes' => [
                'booking_reference' => $booking->booking_reference,
            ],
        ]);
    }

    public function verifyRazorpayPayment(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->payment_status === 'paid') {
            $existingPayment = Payment::where('booking_id', $booking->id)
                ->where('status', 'success')
                ->latest('id')
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Payment already verified.',
                'redirect_url' => $existingPayment
                    ? route('user.payments.success', ['booking' => $booking->id, 'payment' => $existingPayment->id])
                    : route('user.bookings.show', $booking),
            ]);
        }

        $validated = $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $secretKey = PaymentHelper::getRazorpaySecretKey();
        if (empty($secretKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Razorpay secret key is missing.'
            ], 500);
        }

        $payload = $validated['razorpay_order_id'] . '|' . $validated['razorpay_payment_id'];
        $expectedSignature = hash_hmac('sha256', $payload, $secretKey);

        if (!hash_equals($expectedSignature, $validated['razorpay_signature'])) {
            return response()->json([
                'success' => false,
                'message' => 'Payment signature verification failed.'
            ], 422);
        }

        $payment = Payment::firstOrCreate(
            ['transaction_id' => $validated['razorpay_payment_id']],
            [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $booking->total_price,
                'payment_method' => 'razorpay',
                'status' => 'success',
                'payment_date' => now(),
            ]
        );

        if ($payment->wasRecentlyCreated === false && $payment->status !== 'success') {
            $payment->update([
                'status' => 'success',
                'payment_date' => now(),
            ]);
        }

        $booking->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        $booking->seats()->update(['status' => 'booked']);

        // Send payment confirmed email
        $user = $booking->user;
        Mail::to($user->email)->send(new PaymentStatusMail($booking, $payment, 'confirmed'));

        return response()->json([
            'success' => true,
            'message' => 'Payment successful!',
            'redirect_url' => route('user.payments.success', ['booking' => $booking->id, 'payment' => $payment->id]),
        ]);
    }

    private function processStripePayment(Booking $booking)
    {
        // Implementation for Stripe payment
        // This is a placeholder - integrate actual Stripe API
        try {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $booking->total_price,
                'payment_method' => 'stripe',
                'status' => 'pending',
                'payment_date' => now(),
            ]);

            // Keep booking in pending status until admin verifies payment
            // Don't update booking status or send notifications yet

            return redirect()->route('user.payments.pending', ['booking' => $booking->id, 'payment' => $payment->id])
                ->with('success', 'Payment submitted! Awaiting verification.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    private function processPayPalPayment(Booking $booking)
    {
        // Implementation for PayPal payment
        // This is a placeholder - integrate actual PayPal API
        try {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $booking->total_price,
                'payment_method' => 'paypal',
                'status' => 'pending',
                'payment_date' => now(),
            ]);

            // Keep booking in pending status until admin verifies payment
            // Don't update booking status or send notifications yet

            return redirect()->route('user.payments.pending', ['booking' => $booking->id, 'payment' => $payment->id])
                ->with('success', 'Payment submitted! Awaiting verification.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    private function processRazorpayPayment(Booking $booking)
    {
        // Razorpay payment processing
        try {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $booking->total_price,
                'payment_method' => 'razorpay',
                'status' => 'pending',
                'payment_date' => now(),
            ]);

            // Keep booking in pending status until admin verifies payment
            // Don't update booking status or send notifications yet

            return redirect()->route('user.payments.pending', ['booking' => $booking->id, 'payment' => $payment->id])
                ->with('success', 'Payment submitted! Awaiting verification.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    private function processGenericPayment(Booking $booking, string $method, ?string $bankCode = null, ?string $walletProvider = null)
    {
        // Generic success path for UPI / netbanking / wallets
        try {
            $paymentData = [
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $booking->total_price,
                'payment_method' => $method,
                'status' => 'pending',
                'payment_date' => now(),
            ];

            if ($method === 'netbanking' && $bankCode) {
                $paymentData['transaction_id'] = strtoupper($bankCode) . '-' . strtoupper(uniqid('NB'));
            }

            if ($method === 'wallet' && $walletProvider) {
                $paymentData['transaction_id'] = strtoupper($walletProvider) . '-' . strtoupper(uniqid('WL'));
            }

            $payment = Payment::create($paymentData);

            // Keep booking in pending status until admin verifies payment
            // Don't update booking status or send notifications yet

            return redirect()->route('user.payments.pending', ['booking' => $booking->id, 'payment' => $payment->id])
                ->with('success', 'Payment submitted! Awaiting verification.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    public function pending(Booking $booking, Payment $payment)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($payment->booking_id !== $booking->id) {
            abort(403);
        }

        return view('user.payments.pending', compact('booking', 'payment'));
    }

    public function success(Booking $booking, Payment $payment)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($payment->booking_id !== $booking->id) {
            abort(403);
        }

        return view('user.payments.success', compact('booking', 'payment'));
    }

    private function markSeatsAsBooked(Booking $booking)
    {
        // Update all seats associated with this booking to 'booked' status
        $booking->seats()->update(['status' => 'booked']);
    }
}
