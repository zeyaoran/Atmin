<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AdminAuthController extends Controller
{
    public function showLogin(): RedirectResponse|View
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login', [
            'googleConfigured' => $this->googleConfigured(),
        ]);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $admin = Admin::query()
            ->where('email', $credentials['login'])
            ->orWhere('name', $credentials['login'])
            ->first();

        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            return back()
                ->withErrors(['login' => 'The admin name/email or password is incorrect.'])
                ->onlyInput('login');
        }

        Auth::guard('admin')->login($admin, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function redirectToGoogle(): RedirectResponse
    {
        if (! $this->googleConfigured()) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Google login is not configured yet.');
        }

        return Socialite::driver('google')
            ->redirectUrl(config('services.google.redirect'))
            ->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(config('services.google.redirect'))
                ->user();
        } catch (\Throwable) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Google login failed. Please try again.');
        }

        $admin = Admin::where('email', $googleUser->getEmail())->first();

        if (! $admin) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'This Google account is not registered as an admin.');
        }

        $admin->forceFill([
            'name' => $googleUser->getName() ?: $admin->name,
            'image' => $googleUser->getAvatar() ?: $admin->image,
        ])->save();

        Auth::guard('admin')->login($admin, true);
        request()->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    private function googleConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'));
    }
}
