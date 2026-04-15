<?php

namespace App\Http\Controllers\Organiser;

use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
