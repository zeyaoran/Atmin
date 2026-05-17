<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $search = request('search');
        $artistId = request('artist');
        $eventId = request('event');

        $tickets = Ticket::with(['artist', 'event'])
            ->when(filled($search), function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('category', 'like', "%{$search}%")
                        ->orWhereHas('artist', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('event', fn ($q) => $q->where('title', 'like', "%{$search}%"));
                });
            })
            ->when(filled($artistId), fn ($q) => $q->where('artist_id', $artistId))
            ->when(filled($eventId), fn ($q) => $q->where('event_id', $eventId))
            ->latest()
            ->get();

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'artists' => Artist::orderBy('name')->get(),
            'events' => Event::orderBy('title')->get(),
            'search' => $search,
            'artistId' => $artistId,
            'eventId' => $eventId,
        ]);
    }

    public function create()
    {
        return view('admin.tickets.create', [
            'artists' => Artist::orderBy('name')->get(),
            'events' => Event::with('artist')->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'event_id'  => 'nullable|exists:events,id',
            'category'  => 'required|in:VIP,CAT1,CAT2',
            'price'     => 'required|numeric|min:0',
            'stock'     => 'required|integer|min:0', 
        ]);

        $ticket = Ticket::create($validated);

        Event::syncCheapestTicketPrice($ticket->event_id);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    public function edit(Ticket $ticket)
    {
        return view('admin.tickets.edit', [
            'ticket'  => $ticket,
            'artists' => Artist::orderBy('name')->get(),
            'events'  => Event::with('artist')->orderBy('title')->get(),
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'event_id'  => 'nullable|exists:events,id',
            'category'  => 'required|in:VIP,CAT1,CAT2',
            'price'     => 'required|numeric|min:0',
            'stock'     => 'required|integer|min:0', 
        ]);

        $oldEventId = $ticket->event_id;

        $ticket->update($validated);

        Event::syncCheapestTicketPrice($oldEventId);
        Event::syncCheapestTicketPrice($ticket->event_id);

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $eventId = $ticket->event_id;

        $ticket->delete();

        Event::syncCheapestTicketPrice($eventId);

        return back()->with('success', 'Ticket deleted successfully.');
    }
}
