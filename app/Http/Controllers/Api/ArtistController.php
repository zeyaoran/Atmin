<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArtistResource;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    /*
    |----------------------------------
    | GET ALL ARTISTS
    |----------------------------------
    */
    public function index()
    {
        $artists = Artist::with('events.tickets')->withCount('events')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List artists',
            'data' => ArtistResource::collection($artists)
        ]);
    }

    /*
    |----------------------------------
    | GET DETAIL ARTIST
    |----------------------------------
    */
    public function show($id)
    {
        $artist = Artist::with('events.tickets')->find($id);

        if (!$artist) {
            return response()->json([
                'success' => false,
                'message' => 'Artist tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new ArtistResource($artist)
        ]);
    }

    /*
    |----------------------------------
    | CREATE ARTIST
    |----------------------------------
    */
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

        $artist = Artist::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Artist created successfully.',
            'data' => new ArtistResource($artist)
        ], 201);
    }

    /*
    |----------------------------------
    | UPDATE ARTIST
    |----------------------------------
    */
    public function update(Request $request, $id)
    {
        $artist = Artist::find($id);

        if (!$artist) {
            return response()->json([
                'success' => false,
                'message' => 'Artist tidak ditemukan'
            ], 404);
        }

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

        return response()->json([
            'success' => true,
            'message' => 'Artist updated successfully.',
            'data' => new ArtistResource($artist)
        ]);
    }

    /*
    |----------------------------------
    | DELETE ARTIST
    |----------------------------------
    */
    public function destroy($id)
    {
        $artist = Artist::find($id);

        if (!$artist) {
            return response()->json([
                'success' => false,
                'message' => 'Artist tidak ditemukan'
            ], 404);
        }

        if ($artist->image) {
            Storage::disk('public')->delete($artist->image);
        }

        $artist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Artist deleted successfully.'
        ]);
    }
}
