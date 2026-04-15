<?php

namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::whereHas('role', function ($query) {
            $query->where('name', 'User');
        })->count();

        $totalOrganisers = User::whereHas('role', function ($query) {
            $query->where('name', 'Organiser');
        })->count();

        $totalBookings = Booking::count();
        $totalRevenue = Payment::where('status', 'success')->sum('amount');
        $recentBookings = Booking::with('user', 'event')->latest()->limit(10)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOrganisers',
            'totalBookings',
            'totalRevenue',
            'recentBookings'
        ));
    }

    public function users(Request $request)
    {
        $query = User::whereHas('role', function ($q) {
            $q->where('name', 'User');
        });

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function transactions(Request $request)
    {
        $query = Payment::with('booking', 'user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20);

        return view('admin.transactions', compact('transactions'));
    }

    public function bookings(Request $request)
    {
        $query = Booking::with('user', 'event', 'ticket');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('event', function ($eventQuery) use ($search) {
                      $eventQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->latest()->paginate(20);

        return view('admin.bookings', compact('bookings'));
    }

    public function bookingShow(Booking $booking)
    {
        $booking->load(['user', 'event', 'ticket', 'event.organiser']);
        
        return view('admin.booking-detail', compact('booking'));
    }

    public function exportBookings(Request $request)
    {
        $query = Booking::with('user', 'event', 'ticket');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->latest()->get();

        $filename = 'bookings_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Booking ID',
                'User Name',
                'User Email',
                'Event Title',
                'Event Date',
                'Ticket Type',
                'Quantity',
                'Total Price',
                'Status',
                'Payment Status',
                'Booking Date'
            ]);

            // Data rows
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->user->name,
                    $booking->user->email,
                    $booking->event->title,
                    optional($booking->event)->event_date,
                    optional($booking->ticket)->name ?? 'N/A',
                    $booking->quantity ?? 'N/A',
                    $booking->total_amount ?? $booking->total_price ?? 0,
                    $booking->status,
                    $booking->payment_status ?? 'N/A',
                    $booking->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
