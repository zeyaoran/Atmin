<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['artist', 'event']);

        if ($request->event_id) {
            $query->where('event_id', $request->event_id);
        }

        return response()->json([
            'success' => true,
            'data' => $query->latest()->get()
        ]);
    }

    // 📌 STORE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'event_id'  => 'nullable|exists:events,id',
            'category'  => 'required|in:VIP,CAT1,CAT2',
            'price'     => 'required|numeric|min:0',
            'stock'     => 'required|integer|min:0',
        ]);

        $ticket = Ticket::create($validated)->load(['artist', 'event']);

        Event::syncCheapestTicketPrice($ticket->event_id);

        return response()->json([
            'success' => true,
            'message' => 'Ticket created successfully.',
            'data' => new TicketResource($ticket)
        ]);
    }

    // 📌 SHOW
    public function show($id)
    {
        $ticket = Ticket::with(['artist', 'event'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail ticket',
            'data' => new TicketResource($ticket)
        ]);
    }

    // 📌 UPDATE
    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

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

        return response()->json([
            'success' => true,
            'message' => 'Ticket updated successfully.',
            'data' => new TicketResource($ticket->load(['artist', 'event']))
        ]);
    }

    // 📌 DELETE
    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $eventId = $ticket->event_id;

        $ticket->delete();

        Event::syncCheapestTicketPrice($eventId);

        return response()->json([
            'success' => true,
            'message' => 'Ticket deleted successfully.'
        ]);
    }
}
