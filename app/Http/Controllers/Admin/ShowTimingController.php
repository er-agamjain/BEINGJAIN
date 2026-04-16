<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShowTiming;
use App\Models\Ticket;
use Illuminate\Http\Request;

class ShowTimingController extends Controller
{
    public function edit(ShowTiming $showTiming)
    {
        $showTiming->load(['event', 'venue', 'tickets']);
        $tickets = $showTiming->tickets;

        return view('admin.show-timings.edit', compact('showTiming', 'tickets'));
    }

    public function update(Request $request, ShowTiming $showTiming)
    {
        $validated = $request->validate([
            'show_date_time' => 'required|date',
            'duration_minutes' => 'required|integer|min:30',
            'available_seats' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,cancelled,completed',
            'notes' => 'nullable|string',
        ]);

        $allocatedSeats = $showTiming->tickets()->sum('quantity');
        if ($validated['available_seats'] < $allocatedSeats) {
            return redirect()->back()
                ->withErrors(['available_seats' => "Available seats cannot be less than allocated ticket quantity ({$allocatedSeats})."])
                ->withInput();
        }

        $showTiming->update($validated);

        return redirect()->route('admin.show-timings.edit', $showTiming)->with('success', 'Show timing updated successfully!');
    }

    public function storeTicket(Request $request, ShowTiming $showTiming)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'ticket_type' => 'required|in:free,paid',
        ]);

        if ($validated['ticket_type'] === 'free') {
            $validated['price'] = 0;
        }

        $usedCapacity = $showTiming->tickets()->sum('quantity');
        $remaining = $showTiming->available_seats - $usedCapacity;

        if ($validated['quantity'] > $remaining) {
            return redirect()->back()
                ->withErrors(['quantity' => "Only {$remaining} seat(s) unallocated for this show (capacity: {$showTiming->available_seats})."])
                ->withInput()
                ->with('timing_error_id', $showTiming->id);
        }

        $showTiming->tickets()->create(array_merge($validated, [
            'event_id' => $showTiming->event_id,
        ]));

        return redirect()->back()->with('success', 'Ticket category added to show timing.');
    }

    public function updateTicket(Request $request, ShowTiming $showTiming, Ticket $ticket)
    {
        if ((int) $ticket->show_timing_id !== (int) $showTiming->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'ticket_type' => 'required|in:free,paid',
        ]);

        if ($validated['ticket_type'] === 'free') {
            $validated['price'] = 0;
        }

        if ($validated['quantity'] < (int) $ticket->quantity_sold) {
            return redirect()->back()
                ->withErrors(['quantity' => "Quantity cannot be less than already sold seats ({$ticket->quantity_sold})."])
                ->withInput()
                ->with('timing_error_id', $showTiming->id);
        }

        $usedOther = $showTiming->tickets()->where('id', '!=', $ticket->id)->sum('quantity');
        $remainingForThisTicket = $showTiming->available_seats - $usedOther;

        if ($validated['quantity'] > $remainingForThisTicket) {
            return redirect()->back()
                ->withErrors(['quantity' => "Only {$remainingForThisTicket} seat(s) can be allocated to this category (show capacity: {$showTiming->available_seats})."])
                ->withInput()
                ->with('timing_error_id', $showTiming->id);
        }

        $ticket->update($validated);

        return redirect()->back()->with('success', 'Ticket category updated successfully.');
    }

    public function destroyTicket(ShowTiming $showTiming, Ticket $ticket)
    {
        if ((int) $ticket->show_timing_id !== (int) $showTiming->id) {
            abort(404);
        }

        $ticket->delete();

        return redirect()->back()->with('success', 'Ticket category removed.');
    }
}
