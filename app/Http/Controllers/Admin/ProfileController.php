<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        return view('admin.profile', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($admin->id),
            ],
            'password' => 'nullable|min:6|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if (
                $admin->image
                && ! str_starts_with($admin->image, 'http://')
                && ! str_starts_with($admin->image, 'https://')
                && Storage::disk('public')->exists($admin->image)
            ) {
                Storage::disk('public')->delete($admin->image);
            }

            $admin->image = $request->file('image')->store('admins', 'public');
        }

        $admin->save();

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Profile updated successfully.');
    }
}
