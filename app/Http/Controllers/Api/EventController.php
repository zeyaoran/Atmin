<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Resources\EventResource;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // GET ALL
public function index()
{
    $events = Event::with(['artist', 'tickets'])->latest()->get();

    return response()->json([
        'success' => true,
        'data' => EventResource::collection($events)
    ]);
}

public function show($id)
{
    $event = Event::with(['artist', 'tickets'])->find($id);

    if (!$event) {
        return response()->json([
            'success' => false,
            'message' => 'Event tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => new EventResource($event)
    ]);
}

    // CREATE
    public function store(Request $request)
    {
        $request->validate([
            'artist_id' => 'nullable|exists:artists,id',
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'status' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $path = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')
                ->store('events', 'public');
        }

        $event = Event::create([
            'artist_id' => $request->artist_id,
            'title' => $request->title,
            'category' => $request->category,
            'status' => $request->status,
            'city' => $request->city,
            'country' => $request->country,
            'date' => $request->date,
            'price' => $request->price,
            'image' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully.',
            'data' => $event
        ], 201);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'artist_id' => 'nullable|exists:artists,id',
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'status' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        if ($request->hasFile('image')) {

            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }

            $event->image = $request->file('image')
                ->store('events', 'public');
        }

        $event->update([
            'artist_id' => $request->artist_id,
            'title' => $request->title,
            'category' => $request->category,
            'status' => $request->status,
            'city' => $request->city,
            'country' => $request->country,
            'date' => $request->date,
            'price' => $request->price,
            'image' => $event->image,
        ]);

        Event::syncCheapestTicketPrice($event->id);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully.',
            'data' => $event
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event tidak ditemukan'
            ], 404);
        }

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully.'
        ]);
    }
}
