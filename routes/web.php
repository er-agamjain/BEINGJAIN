<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\OrganiserController;
use App\Http\Controllers\Admin\EventCategoryController;
use App\Http\Controllers\Admin\EventCommunityController;
use App\Http\Controllers\Admin\EventGacchController;
// Temporary — remove after running once on production
use App\Http\Controllers\Admin\EventTagController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\RefundController;
use App\Http\Controllers\Admin\PlatformSettingController;
use App\Http\Controllers\Admin\SeatController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\VenueController as AdminVenueController;
use App\Http\Controllers\Admin\SeatCategoryController as AdminSeatCategoryController;
use App\Http\Controllers\Admin\ShowTimingController as AdminShowTimingController;
use App\Http\Controllers\Organiser\EventController as OrganiserEventController;
use App\Http\Controllers\Organiser\TicketController as OrganiserTicketController;
use App\Http\Controllers\Organiser\BookingController as OrganiserBookingController;
use App\Http\Controllers\Organiser\PaymentVerificationController as OrganiserPaymentVerificationController;
use App\Http\Controllers\Organiser\VenueController;
use App\Http\Controllers\Organiser\ShowTimingController;
use App\Http\Controllers\Organiser\SeatCategoryController;
use App\Http\Controllers\User\EventController as UserEventController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\PaymentHistoryController;
use App\Http\Controllers\User\TicketController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Page Routes
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

// Debug route to check current user
Route::get('/debug-user', function () {
    /** @var \App\Models\User|null $user */
    $user = Auth::user();

    if (!$user) {
        return response()->json(['authenticated' => false]);
    }

    $user->load('role');

    return response()->json([
        'authenticated' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'role_name' => $user->role?->name,
        ],
        'isAdmin' => $user->isAdmin(),
        'isOrganiser' => $user->isOrganiser(),
        'isUser' => $user->isUser(),
    ]);
})->middleware('auth');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Google Auth
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

// Public seat availability (needed before auth)
Route::get('/user/show-timings/{showTiming}/seats', [BookingController::class, 'getSeats'])->name('user.show-timings.seats');

// User Routes
Route::middleware(['auth', 'role:User'])->prefix('user')->name('user.')->group(function () {
    // Events
    Route::get('/events', [UserEventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [UserEventController::class, 'show'])->name('events.show');

    // Bookings
    Route::post('/bookings/{event}', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/history', [BookingController::class, 'history'])->name('bookings.history');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

    // Payments
    Route::get('/payments', [PaymentHistoryController::class, 'index'])->name('payments.index');
    Route::get('/payments/{booking}', [PaymentController::class, 'create'])->name('payments.show');
    Route::get('/payments/{booking}/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments/{booking}', [PaymentController::class, 'store'])->name('payments.store');
    Route::post('/payments/{booking}/razorpay/order', [PaymentController::class, 'createRazorpayOrder'])->name('payments.razorpay.order');
    Route::post('/payments/{booking}/razorpay/verify', [PaymentController::class, 'verifyRazorpayPayment'])->name('payments.razorpay.verify');
    Route::post('/payments/{booking}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::get('/payments/{booking}/{payment}/pending', [PaymentController::class, 'pending'])->name('payments.pending');
    Route::get('/payments/{booking}/{payment}/success', [PaymentController::class, 'success'])->name('payments.success');

    // Tickets
    Route::get('/tickets/{booking}/view', [TicketController::class, 'view'])->name('tickets.view');
    Route::get('/tickets/{booking}/download', [TicketController::class, 'downloadPdf'])->name('tickets.download');
});

// Organiser Routes
Route::middleware(['auth', 'role:Organiser', 'ensure_organiser_active'])->prefix('organiser')->name('organiser.')->group(function () {
    // Events
    Route::get('/events', [OrganiserEventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [OrganiserEventController::class, 'create'])->name('events.create');
    Route::post('/events', [OrganiserEventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [OrganiserEventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [OrganiserEventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [OrganiserEventController::class, 'update'])->name('events.update');
    Route::post('/events/{event}/publish', [OrganiserEventController::class, 'publish'])->name('events.publish');
    Route::delete('/events/{event}', [OrganiserEventController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/{event}/timings', [OrganiserEventController::class, 'storeTiming'])->name('events.timings.store');
    Route::delete('/timings/{timing}', [OrganiserEventController::class, 'destroyTiming'])->name('events.timings.destroy');

    // Venues
    Route::get('/venues', [VenueController::class, 'index'])->name('venues.index');
    Route::get('/venues/create', [VenueController::class, 'create'])->name('venues.create');
    Route::get('/search-cities', [VenueController::class, 'searchCities'])->name('search-cities');
    Route::post('/venues', [VenueController::class, 'store'])->name('venues.store');
    Route::get('/venues/{venue}/edit', [VenueController::class, 'edit'])->name('venues.edit');
    Route::put('/venues/{venue}', [VenueController::class, 'update'])->name('venues.update');
    Route::delete('/venues/{venue}', [VenueController::class, 'destroy'])->name('venues.destroy');

    // Show Timings
    Route::get('/show-timings', [ShowTimingController::class, 'index'])->name('show-timings.index');
    Route::get('/show-timings/create', [ShowTimingController::class, 'create'])->name('show-timings.create');
    Route::post('/show-timings', [ShowTimingController::class, 'store'])->name('show-timings.store');
    Route::get('/show-timings/{showTiming}/edit', [ShowTimingController::class, 'edit'])->name('show-timings.edit');
    Route::put('/show-timings/{showTiming}', [ShowTimingController::class, 'update'])->name('show-timings.update');
    Route::post('/show-timings/{showTiming}/block-seats', [ShowTimingController::class, 'blockSeats'])->name('show-timings.block-seats');

    // Seat Categories
    Route::get('/venues/{venue}/seat-categories', [SeatCategoryController::class, 'index'])->name('seat-categories.index');
    Route::get('/venues/{venue}/seat-categories/create', [SeatCategoryController::class, 'create'])->name('seat-categories.create');
    Route::post('/venues/{venue}/seat-categories', [SeatCategoryController::class, 'store'])->name('seat-categories.store');
    Route::get('/venues/{venue}/seat-categories/{seatCategory}/edit', [SeatCategoryController::class, 'edit'])->name('seat-categories.edit');
    Route::put('/venues/{venue}/seat-categories/{seatCategory}', [SeatCategoryController::class, 'update'])->name('seat-categories.update');
    Route::delete('/venues/{venue}/seat-categories/{seatCategory}', [SeatCategoryController::class, 'destroy'])->name('seat-categories.destroy');

    // Tickets
    Route::get('/events/{event}/tickets/create', [OrganiserTicketController::class, 'create'])->name('tickets.create');
    Route::post('/events/{event}/tickets', [OrganiserTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}/edit', [OrganiserTicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [OrganiserTicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [OrganiserTicketController::class, 'destroy'])->name('tickets.destroy');

        // Show-timing scoped tickets
        Route::post('/show-timings/{showTiming}/tickets', [OrganiserTicketController::class, 'storeForTiming'])->name('show-timings.tickets.store');
        Route::put('/show-timings/{showTiming}/tickets/{ticket}', [OrganiserTicketController::class, 'updateForTiming'])->name('show-timings.tickets.update');
        Route::delete('/show-timings/{showTiming}/tickets/{ticket}', [OrganiserTicketController::class, 'destroyForTiming'])->name('show-timings.tickets.destroy');

    // Bookings
    Route::get('/bookings', [OrganiserBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/history', [OrganiserBookingController::class, 'history'])->name('bookings.history');

    // Payments
    Route::get('/payments/pending', [OrganiserPaymentVerificationController::class, 'index'])->name('payments.pending');
    Route::get('/payments/history', [OrganiserPaymentVerificationController::class, 'history'])->name('payments.history');
    Route::post('/payments/{payment}/approve', [OrganiserPaymentVerificationController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{payment}/reject', [OrganiserPaymentVerificationController::class, 'reject'])->name('payments.reject');
    Route::post('/payments/{payment}/not-received', [OrganiserPaymentVerificationController::class, 'markNotReceived'])->name('payments.not-received');
    Route::post('/payments/{payment}/update-status', [OrganiserPaymentVerificationController::class, 'updateStatus'])->name('payments.update-status');
});

// Admin Routes
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::get('/users', [AdminDashboard::class, 'users'])->name('users.index');
    Route::get('/transactions', [AdminDashboard::class, 'transactions'])->name('transactions.index');
    Route::get('/bookings', [AdminDashboard::class, 'bookings'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminDashboard::class, 'bookingShow'])->name('bookings.show');
    Route::get('/bookings-export', [AdminDashboard::class, 'exportBookings'])->name('bookings.export');

    // Events Management (Admin)
    Route::get('/events', [\App\Http\Controllers\Admin\EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [\App\Http\Controllers\Admin\EventController::class, 'create'])->name('events.create');
    Route::post('/events', [\App\Http\Controllers\Admin\EventController::class, 'store'])->name('events.store');

    // Payment Verification
    Route::get('/payments/pending', [PaymentVerificationController::class, 'index'])->name('payments.pending');
    Route::patch('/payments/{payment}/approve', [PaymentVerificationController::class, 'approve'])->name('payments.approve');
    Route::patch('/payments/{payment}/reject', [PaymentVerificationController::class, 'reject'])->name('payments.reject');
    Route::patch('/payments/{payment}/not-received', [PaymentVerificationController::class, 'markNotReceived'])->name('payments.not-received');

    // Cities
    Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
    Route::post('/cities', [CityController::class, 'store'])->name('cities.store');
    Route::put('/cities/{city}', [CityController::class, 'update'])->name('cities.update');
    Route::delete('/cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');
    Route::post('/cities/{city}/toggle', [CityController::class, 'toggleActive'])->name('cities.toggle');

    // Venues
    Route::get('/venues', [AdminVenueController::class, 'index'])->name('venues.index');
    Route::get('/venues/create', [AdminVenueController::class, 'create'])->name('venues.create');
    Route::get('/search-cities', [AdminVenueController::class, 'searchCities'])->name('search-cities');
    Route::post('/venues', [AdminVenueController::class, 'store'])->name('venues.store');
    Route::get('/venues/{venue}/edit', [AdminVenueController::class, 'edit'])->name('venues.edit');
    Route::put('/venues/{venue}', [AdminVenueController::class, 'update'])->name('venues.update');
    Route::delete('/venues/{venue}', [AdminVenueController::class, 'destroy'])->name('venues.destroy');

    // Venue Seat Categories
    Route::get('/venues/{venue}/seat-categories', [AdminSeatCategoryController::class, 'index'])->name('seat-categories.index');
    Route::post('/venues/{venue}/seat-categories', [AdminSeatCategoryController::class, 'store'])->name('seat-categories.store');
    Route::put('/venues/{venue}/seat-categories/{seatCategory}', [AdminSeatCategoryController::class, 'update'])->name('seat-categories.update');
    Route::delete('/venues/{venue}/seat-categories/{seatCategory}', [AdminSeatCategoryController::class, 'destroy'])->name('seat-categories.destroy');

    // Refunds
    Route::get('/refunds', [RefundController::class, 'index'])->name('refunds.index');
    Route::get('/refunds/{refund}', [RefundController::class, 'show'])->name('refunds.show');
    Route::put('/refunds/{refund}/approve', [RefundController::class, 'approve'])->name('refunds.approve');
    Route::put('/refunds/{refund}/reject', [RefundController::class, 'reject'])->name('refunds.reject');
    Route::put('/refunds/{refund}/complete', [RefundController::class, 'complete'])->name('refunds.complete');

    // Platform Settings
    Route::get('/settings', [PlatformSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [PlatformSettingController::class, 'update'])->name('settings.update');

    // Organisers
    Route::get('/organisers', [OrganiserController::class, 'index'])->name('organisers.index');
    Route::post('/organisers', [OrganiserController::class, 'store'])->name('organisers.store');
    Route::put('/organisers/{user}/activate', [OrganiserController::class, 'activate'])->name('organisers.activate');
    Route::put('/organisers/{user}/deactivate', [OrganiserController::class, 'deactivate'])->name('organisers.deactivate');
    Route::put('/organisers/{user}/commission', [OrganiserController::class, 'updateCommission'])->name('organisers.commission');
    Route::delete('/organisers/{user}', [OrganiserController::class, 'destroy'])->name('organisers.destroy');
    
    // Event show/edit/update/approve/reject/delete routes (using EventController)
    Route::get('/events/{event}', [\App\Http\Controllers\Admin\EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [\App\Http\Controllers\Admin\EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [\App\Http\Controllers\Admin\EventController::class, 'update'])->name('events.update');
    
     // Tickets (seats)
    Route::post('/events/{event}/tickets', [\App\Http\Controllers\Admin\EventController::class, 'storeTicket'])->name('events.tickets.store');
    Route::delete('/tickets/{ticket}', [\App\Http\Controllers\Admin\EventController::class, 'destroyTicket'])->name('events.tickets.destroy');
    // Show timings
    Route::post('/events/{event}/timings', [\App\Http\Controllers\Admin\EventController::class, 'storeTiming'])->name('events.timings.store');
    Route::delete('/timings/{timing}', [\App\Http\Controllers\Admin\EventController::class, 'destroyTiming'])->name('events.timings.destroy');
    Route::get('/show-timings/{showTiming}/edit', [AdminShowTimingController::class, 'edit'])->name('show-timings.edit');
    Route::put('/show-timings/{showTiming}', [AdminShowTimingController::class, 'update'])->name('show-timings.update');
    Route::post('/show-timings/{showTiming}/tickets', [AdminShowTimingController::class, 'storeTicket'])->name('show-timings.tickets.store');
    Route::put('/show-timings/{showTiming}/tickets/{ticket}', [AdminShowTimingController::class, 'updateTicket'])->name('show-timings.tickets.update');
    Route::delete('/show-timings/{showTiming}/tickets/{ticket}', [AdminShowTimingController::class, 'destroyTicket'])->name('show-timings.tickets.destroy');

    // Event approve/reject/delete routes (using EventController)
    Route::put('/events/{event}/approve', [\App\Http\Controllers\Admin\EventController::class, 'approve'])->name('events.approve');
    Route::put('/events/{event}/reject', [\App\Http\Controllers\Admin\EventController::class, 'reject'])->name('events.reject');
    Route::delete('/events/{event}', [\App\Http\Controllers\Admin\EventController::class, 'destroy'])->name('events.destroy');

    // Event Categories
    Route::get('/event-categories', [EventCategoryController::class, 'index'])->name('event-categories.index');
    Route::post('/event-categories', [EventCategoryController::class, 'store'])->name('event-categories.store');
    Route::post('/event-categories/import', [EventCategoryController::class, 'import'])->name('event-categories.import');
    Route::put('/event-categories/{category}', [EventCategoryController::class, 'update'])->name('event-categories.update');
    Route::delete('/event-categories/{category}', [EventCategoryController::class, 'destroy'])->name('event-categories.destroy');
    Route::post('/event-categories/{category}/toggle', [EventCategoryController::class, 'toggleActive'])->name('event-categories.toggle');

    // Event Communities
    Route::get('/event-communities', [EventCommunityController::class, 'index'])->name('event-communities.index');
    Route::post('/event-communities', [EventCommunityController::class, 'store'])->name('event-communities.store');
    Route::post('/event-communities/import', [EventCommunityController::class, 'import'])->name('event-communities.import');
    Route::put('/event-communities/{community}', [EventCommunityController::class, 'update'])->name('event-communities.update');
    Route::delete('/event-communities/{community}', [EventCommunityController::class, 'destroy'])->name('event-communities.destroy');
    Route::post('/event-communities/{community}/toggle', [EventCommunityController::class, 'toggleActive'])->name('event-communities.toggle');

    // Event Gacchs
    Route::get('/event-gacchs', [EventGacchController::class, 'index'])->name('event-gacchs.index');
    Route::post('/event-gacchs', [EventGacchController::class, 'store'])->name('event-gacchs.store');
    Route::post('/event-gacchs/import', [EventGacchController::class, 'import'])->name('event-gacchs.import');
    Route::put('/event-gacchs/{gacchh}', [EventGacchController::class, 'update'])->name('event-gacchs.update');
    Route::delete('/event-gacchs/{gacchh}', [EventGacchController::class, 'destroy'])->name('event-gacchs.destroy');
    Route::post('/event-gacchs/{gacchh}/toggle', [EventGacchController::class, 'toggleActive'])->name('event-gacchs.toggle');

    // Event Tags
    Route::get('/event-tags', [EventTagController::class, 'index'])->name('event-tags.index');
    Route::post('/event-tags', [EventTagController::class, 'store'])->name('event-tags.store');
    Route::post('/event-tags/import', [EventTagController::class, 'import'])->name('event-tags.import');
    Route::put('/event-tags/{tag}', [EventTagController::class, 'update'])->name('event-tags.update');
    Route::delete('/event-tags/{tag}', [EventTagController::class, 'destroy'])->name('event-tags.destroy');
    Route::post('/event-tags/{tag}/toggle', [EventTagController::class, 'toggleActive'])->name('event-tags.toggle');

    // Seats Management
    Route::get('/seats', [SeatController::class, 'index'])->name('seats.index');
    Route::get('/seats/{seat}', [SeatController::class, 'show'])->name('seats.show');
    Route::get('/seats/{seat}/edit', [SeatController::class, 'edit'])->name('seats.edit');
    Route::put('/seats/{seat}', [SeatController::class, 'update'])->name('seats.update');
    Route::post('/seats/bulk-update', [SeatController::class, 'bulkUpdate'])->name('seats.bulkUpdate');
    Route::post('/seats/block', [SeatController::class, 'blockSeats'])->name('seats.block');
    Route::post('/seats/unblock', [SeatController::class, 'unblockSeats'])->name('seats.unblock');
    Route::get('/seats/stats', [SeatController::class, 'stats'])->name('seats.stats');
    
    
});

require __DIR__ . '/auth.php';
