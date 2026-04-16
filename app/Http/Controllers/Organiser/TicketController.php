<?php

namespace App\Http\Controllers\Organiser;

use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ShowTiming;

class TicketController extends Controller
{
    public function create(Event $event)
    {
        if ((int)$event->organiser_id !== (int)Auth::id()) {
            abort(403);
        }

        return view('organiser.tickets.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        if ((int)$event->organiser_id !== (int)Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'ticket_type' => 'required|in:free,paid',
        ]);

        if ($validated['ticket_type'] === 'free') {
            $validated['price'] = 0;
        }

        $event->tickets()->create($validated);

        return redirect()->route('organiser.events.show', $event)->with('success', 'Ticket created');
    }

    public function edit(Ticket $ticket)
    {
        if ((int)$ticket->event->organiser_id !==(int) Auth::id()) {
            abort(403);
        }

        $ticket->load('event');
        return view('organiser.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        if ((int)$ticket->event->organiser_id !== (int)Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'ticket_type' => 'required|in:free,paid',
        ]);

        if ($validated['ticket_type'] === 'free') {
            $validated['price'] = 0;
        }

        $ticket->update($validated);

        return redirect()->route('organiser.events.show', $ticket->event)->with('success', 'Ticket updated');
    }

    public function destroy(Ticket $ticket)
    {
        if ((int)$ticket->event->organiser_id !== (int)Auth::id()) {
            abort(403);
        }

        $ticket->delete();
        return redirect()->back()->with('success', 'Ticket deleted');
    }

        // ── Show-Timing-scoped ticket management ─────────────────────────────────

        public function storeForTiming(Request $request, ShowTiming $showTiming)
        {
            $event = $showTiming->event;
            if ((int)$event->organiser_id !== (int)Auth::id()) {
                abort(403);
            }

            $validated = $request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
                'price'       => 'nullable|numeric|min:0',
                'quantity'    => 'required|integer|min:1',
                'ticket_type' => 'required|in:free,paid',
            ]);

            if ($validated['ticket_type'] === 'free') {
                $validated['price'] = 0;
            }

            // Capacity check: sum of existing tickets for this show timing
            $usedCapacity = $showTiming->tickets()->sum('quantity');
            $remaining    = $showTiming->available_seats - $usedCapacity;

            if ($validated['quantity'] > $remaining) {
                return redirect()->back()
                    ->withErrors(['quantity' => "Only {$remaining} seat(s) unallocated for this show (capacity: {$showTiming->available_seats})."])
                    ->withInput()
                    ->with('timing_error_id', $showTiming->id);
            }

            $showTiming->tickets()->create(array_merge($validated, [
                'event_id' => $event->id,
            ]));

            return redirect()->back()->with('success', 'Ticket category added to show timing.');
        }

        public function updateForTiming(Request $request, ShowTiming $showTiming, Ticket $ticket)
        {
            $event = $showTiming->event;
            if ((int)$event->organiser_id !== (int)Auth::id()) {
                abort(403);
            }

            if ((int)$ticket->show_timing_id !== (int)$showTiming->id) {
                abort(403);
            }

            $validated = $request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
                'price'       => 'nullable|numeric|min:0',
                'quantity'    => 'required|integer|min:1',
                'ticket_type' => 'required|in:free,paid',
            ]);

            if ($validated['ticket_type'] === 'free') {
                $validated['price'] = 0;
            }

            if ($validated['quantity'] < (int)$ticket->quantity_sold) {
                return redirect()->back()
                    ->withErrors(['quantity' => "Quantity cannot be less than already sold seats ({$ticket->quantity_sold})."])
                    ->withInput()
                    ->with('timing_error_id', $showTiming->id);
            }

            // Capacity check excluding the current ticket quantity
            $usedOther = $showTiming->tickets()
                ->where('id', '!=', $ticket->id)
                ->sum('quantity');
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

        public function destroyForTiming(ShowTiming $showTiming, Ticket $ticket)
        {
            $event = $showTiming->event;
            if ((int)$event->organiser_id !== (int)Auth::id()) {
                abort(403);
            }

            if ((int)$ticket->show_timing_id !== (int)$showTiming->id) {
                abort(403);
            }

            $ticket->delete();
            return redirect()->back()->with('success', 'Ticket category removed.');
        }
}
