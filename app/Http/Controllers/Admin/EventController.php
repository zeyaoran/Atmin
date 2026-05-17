<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    private const EVENT_CATEGORIES = ['World Tour', 'Concert'];
    private const EVENT_TAGS = ['HOT', 'NEW', 'VIP'];

    private function storeEventImage(UploadedFile $image): string
    {
        Storage::disk('public')->makeDirectory('events');

        return $image->storePubliclyAs('events', $image->hashName(), 'public');
    }

    public function index()
    {
        $search = request('search');
        $country = request('country');
        $month = request('month');
        $category = request('category');

        $events = Event::with('artist')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('artist', function ($a) use ($search) {
                    $a->where('name', 'like', "%{$search}%");
                });
            })
            ->when($country, fn ($q) => $q->where('country', $country))
            ->when($month, fn ($q) => $q->whereMonth('date', $month))
            ->when($category, fn ($q) => $q->where('category', $category))
            ->latest()
            ->get();

        $countries = Event::select('country')->distinct()->pluck('country');
        $categories = Event::select('category')->distinct()->pluck('category');

        $months = collect(range(1, 12))->map(fn ($m) => [
            'value' => $m,
            'label' => Carbon::create()->month($m)->format('F'),
        ]);

        return view('admin.events.index', [
            'events' => $events,
            'countries' => $countries,
            'categories' => $categories,
            'months' => $months,
            'search' => $search ?? '',
            'country' => $country ?? '',
            'month' => $month ?? '',
            'category' => $category ?? '',
        ]);
    }

    public function create()
    {
        return view('admin.events.create', [
            'artists' => Artist::orderBy('name')->get(),
            'eventCategories' => self::EVENT_CATEGORIES,
            'eventTags' => self::EVENT_TAGS,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'artist_id' => 'nullable|exists:artists,id',
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', self::EVENT_CATEGORIES),
            'status' => 'required|string|in:' . implode(',', self::EVENT_TAGS),

            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',

            'date' => 'nullable|date',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $path = null;

        if ($request->hasFile('image')) {
            $path = $this->storeEventImage($request->file('image'));
        }

        Event::create([
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

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show($id)
    {
        $event = Event::with(['artist', 'tickets' => fn ($query) => $query->orderBy('price')])->findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    public function edit($id)
    {
        return view('admin.events.edit', [
            'event' => Event::findOrFail($id),
            'artists' => Artist::orderBy('name')->get(),
            'eventCategories' => self::EVENT_CATEGORIES,
            'eventTags' => self::EVENT_TAGS,
        ]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'artist_id' => 'nullable|exists:artists,id',
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', self::EVENT_CATEGORIES),
            'status' => 'required|string|in:' . implode(',', self::EVENT_TAGS),
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',

            'date' => 'nullable|date',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $event->image = $this->storeEventImage($request->file('image'));
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

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return back()->with('success', 'Event deleted successfully.');
    }
}
