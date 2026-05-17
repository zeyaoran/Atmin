<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = Artist::latest()->get();

        return view('admin.artists.index', compact('artists'));
    }

    public function create()
    {
        return view('admin.artists.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $path = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('artists', 'public');
        }

        Artist::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $path,
        ]);

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist created successfully.');
    }

    public function show($id)
    {
        $artist = Artist::with([
            'events' => fn ($query) => $query->withCount('tickets')->orderByDesc('date')->orderByDesc('id'),
            'events.tickets',
        ])->findOrFail($id);

        return view('admin.artists.show', compact('artist'));
    }

    public function edit($id)
    {
        $artist = Artist::findOrFail($id);

        return view('admin.artists.edit', compact('artist'));
    }

    public function update(Request $request, $id)
    {
        $artist = Artist::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        if ($request->hasFile('image')) {
            if ($artist->image) {
                Storage::disk('public')->delete($artist->image);
            }

            $artist->image = $request->file('image')->store('artists', 'public');
        }

        $artist->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $artist->image,
        ]);

        return redirect()->route('admin.artists.index')
            ->with('success', 'Artist updated successfully.');
    }

    public function destroy($id)
    {
        $artist = Artist::findOrFail($id);

        if ($artist->image) {
            Storage::disk('public')->delete($artist->image);
        }

        $artist->delete();

        return back()->with('success', 'Artist deleted successfully.');
    }
}
