<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class UserAuthController extends Controller
{
    /*REGISTER*/

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'image' => null,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'message' => 'Register success',
            'user' => $user->fresh(),
            'token' => $token,
        ], 201);
    }

    /*LOGIN*/

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Login failed.'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('user_token')->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'user' => $user->fresh(),
            'token' => $token,
        ]);
    }

    /*LOGOUT*/

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout successful.'
        ]);
    }

    /*ME*/

    public function me(Request $request)
    {
        // Selalu ambil data terbaru dari database
        return response()->json([
            'user' => $request->user()->fresh(),
        ]);
    }

    /*UPDATE PROFILE*/

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user->name = $validated['name'];

        if ($request->hasFile('image')) {

            if (
                $user->image &&
                !Str::startsWith($user->image, ['http://', 'https://']) &&
                Storage::disk('public')->exists($user->image)
            ) {
                Storage::disk('public')->delete($user->image);
            }

            $user->image = $request->file('image')->store('users', 'public');
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated',
            'user' => $user->fresh(),
        ]);
    }

    /*GOOGLE REDIRECT*/

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl(config('services.google.user_redirect'))
            ->stateless()
            ->redirect();
    }

    /*GOOGLE CALLBACK*/

    public function handleGoogleCallback()
    {
        $reactUrl = rtrim((string) config('services.react.url'), '/');

        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(config('services.google.user_redirect'))
                ->stateless()
                ->user();
        } catch (\Throwable $exception) {
            return redirect()->away($reactUrl . '/login?error=google');
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name'              => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
                'email'             => $googleUser->getEmail(),
                'image'             => null,
                'password'          => Hash::make(Str::random(40)),
                'email_verified_at' => now(),
            ]);
        }

        $token = $user->createToken('google_user_token')->plainTextToken;

        return redirect()->away(
            $reactUrl . '/google-success?token=' . urlencode($token) . '&from=google'
        );
    }
}