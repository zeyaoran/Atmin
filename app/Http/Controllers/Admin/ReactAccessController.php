<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ReactAccessController extends Controller
{
    public function open(): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $user = User::firstOrCreate(
            ['email' => $admin->email],
            [
                'name' => $admin->name,
                'password' => Hash::make(Str::random(40)),
                'image' => $admin->image,
            ]
        );

        $user->forceFill([
            'name' => $admin->name,
            'image' => $admin->image,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        $token = $user->createToken('admin_dashboard_react')->plainTextToken;
        $reactUrl = rtrim((string) config('services.react.url'), '/');

        return redirect()->away($reactUrl . '/admin-login?token=' . urlencode($token) . '&from=admin');
    }
}
