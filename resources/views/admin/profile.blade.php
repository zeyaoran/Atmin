@extends('layouts.admin')

@section('content')
<section class="relative mx-auto max-w-6xl">

    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute left-8 top-0 h-72 w-72 rounded-full bg-fuchsia-500/10 blur-3xl"></div>
        <div class="absolute right-8 top-36 h-80 w-80 rounded-full bg-pink-500/10 blur-3xl"></div>
    </div>

    <div class="space-y-6">

        @if ($errors->any())
            <div class="relative overflow-hidden rounded-2xl border border-red-300/20 bg-red-400/10 p-4 text-sm text-red-100 shadow-2xl shadow-red-950/20 backdrop-blur-xl">
                <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b from-red-300 to-pink-400"></div>
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-red-300/15 text-red-100 ring-1 ring-red-200/20">
                        <i class="ri-error-warning-line text-lg"></i>
                    </div>
                    <p class="font-medium">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

        <div>
            <p class="text-xs uppercase tracking-[0.32em] text-slate-500">Admin Settings</p>
            <h1 class="mt-2 text-2xl font-semibold tracking-tight text-white lg:text-3xl">Profile</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-400">
                Manage your admin identity, profile photo, and login security in one place.
            </p>
        </div>

        <form id="profileForm" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="overflow-hidden rounded-[30px] border border-white/10 bg-white/[0.055] shadow-2xl shadow-black/25 backdrop-blur-2xl">
                <div class="h-24 bg-gradient-to-r from-fuchsia-500/35 via-pink-500/20 to-slate-950"></div>

                <div class="grid gap-8 p-5 lg:grid-cols-[290px_minmax(0,1fr)] lg:p-7">
                    <div class="-mt-16 flex flex-col items-center rounded-[26px] border border-white/10 bg-[#111118]/95 p-5 text-center shadow-2xl shadow-black/20">
                        <div class="relative h-32 w-32">
                            <div class="absolute -inset-2 rounded-full bg-gradient-to-br from-fuchsia-500/45 to-pink-500/35 blur-xl"></div>

                            <div class="group relative h-32 w-32 overflow-hidden rounded-full border-4 border-[#111118] bg-gradient-to-br from-fuchsia-500 to-pink-500 shadow-xl shadow-fuchsia-950/40">
                                @if($admin->image_url)
                                    @php
                                        $adminImageUrl = $admin->image_url;
                                        $adminImageVersion = optional($admin->updated_at)->timestamp;
                                    @endphp
                                    <img id="avatarPreview" src="{{ $adminImageUrl }}{{ str_contains($adminImageUrl, '?') ? '&' : '?' }}v={{ $adminImageVersion }}" alt="{{ $admin->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div id="avatarFallback" class="flex h-full w-full items-center justify-center text-4xl font-black text-white">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                    <img id="avatarPreview" src="" alt="{{ $admin->name }}" class="hidden h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @endif
                            </div>

                            <label class="absolute bottom-0 right-0 flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border-4 border-[#111118] bg-white text-slate-900 shadow-xl transition hover:scale-105 hover:bg-slate-100">
                                <i class="ri-camera-line"></i>
                                <input id="avatarInput" type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="hidden">
                            </label>
                        </div>

                        <h2 class="mt-5 max-w-full truncate text-xl font-semibold text-white">{{ $admin->name }}</h2>
                        <p class="mt-1 max-w-full truncate text-sm text-slate-400">{{ $admin->email }}</p>

                        <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-emerald-300/20 bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-100">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                            Active admin
                        </div>

                        <p class="mt-5 text-xs leading-5 text-slate-500">
                            Click the camera icon to change your photo, then save your changes below.
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-white/10 bg-white/[0.06] text-fuchsia-100">
                                    <i class="ri-user-settings-line text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-white">Account Information</h2>
                                    <p class="text-sm text-slate-400">Basic information for the admin account.</p>
                                </div>
                            </div>

                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                <div class="group">
                                    <label class="mb-2 block text-[13px] font-medium text-slate-300">Display Name</label>
                                    <div class="relative">
                                        <i class="ri-user-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 transition group-focus-within:text-fuchsia-200"></i>
                                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="field pl-11 text-sm" required>
                                    </div>
                                </div>

                                <div class="group">
                                    <label class="mb-2 block text-[13px] font-medium text-slate-300">Email Address</label>
                                    <div class="relative">
                                        <i class="ri-mail-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 transition group-focus-within:text-fuchsia-200"></i>
                                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="field pl-11 text-sm" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-white/10"></div>

                        <div>
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-fuchsia-300/15 bg-fuchsia-400/10 text-fuchsia-100">
                                    <i class="ri-shield-keyhole-line text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-white">Security</h2>
                                    <p class="text-sm text-slate-400">Leave the password fields empty if you do not want to change it.</p>
                                </div>
                            </div>

                            <div class="mt-5 grid gap-4 md:grid-cols-2">
                                <div class="group">
                                    <label class="mb-2 block text-[13px] font-medium text-slate-300">New Password</label>
                                    <div class="relative">
                                        <i class="ri-lock-password-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 transition group-focus-within:text-fuchsia-200"></i>
                                        <input type="password" name="password" class="field pl-11 text-sm" placeholder="New password">
                                    </div>
                                </div>

                                <div class="group">
                                    <label class="mb-2 block text-[13px] font-medium text-slate-300">Confirm Password</label>
                                    <div class="relative">
                                        <i class="ri-shield-check-line pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 transition group-focus-within:text-fuchsia-200"></i>
                                        <input type="password" name="password_confirmation" class="field pl-11 text-sm" placeholder="Repeat password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-[26px] border border-white/10 bg-[#111118]/90 p-4 shadow-2xl shadow-black/30 backdrop-blur-xl">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-white">Save settings</p>
                        <p class="mt-1 text-xs text-slate-400">Profile and security changes will be saved to the admin account.</p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('admin.profile') }}" class="btn-secondary smooth text-sm">
                            Reset
                        </a>

                        <button type="submit" class="btn-primary smooth text-sm">
                            <i class="ri-save-3-line"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="rounded-[26px] border border-white/10 bg-white/[0.045] p-5 backdrop-blur-xl">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-white/10 bg-white/[0.06] text-slate-200">
                        <i class="ri-logout-circle-r-line text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-white">Logout</h2>
                        <p class="mt-1 text-sm text-slate-400">End this browser session without deleting account data.</p>
                    </div>
                </div>

                <button
                    type="button"
                    id="openLogoutConfirm"
                    class="rounded-full border border-white/10 bg-white/[0.07] px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/[0.11]"
                >
                    Log out of this session
                </button>
            </div>
        </div>

        <form id="logoutForm" method="POST" action="{{ route('admin.logout') }}">
            @csrf
        </form>
    </div>
</section>

<div id="logoutConfirmModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/70 px-4 backdrop-blur-md">
    <div class="w-full max-w-md rounded-[28px] border border-white/10 bg-[#111118] p-6 shadow-2xl shadow-black/40">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-red-300/20 bg-red-500/15 text-red-100">
            <i class="ri-logout-circle-r-line text-xl"></i>
        </div>
        <h2 class="mt-5 text-xl font-bold text-white">Log out?</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">You will end this admin session in the current browser. Your account data will stay safe.</p>
        <div class="mt-6 grid grid-cols-2 gap-3">
            <button type="button" id="cancelLogoutConfirm" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                Cancel
            </button>
            <button type="button" id="confirmLogoutSubmit" class="rounded-2xl bg-gradient-to-r from-red-500 to-pink-600 px-4 py-3 text-sm font-bold text-white transition hover:brightness-110">
                Log Out
            </button>
        </div>
    </div>
</div>

<script>
const avatarInput = document.getElementById('avatarInput');
const previewTargets = [
    document.getElementById('avatarPreview')
].filter(Boolean);
const fallbackTargets = [
    document.getElementById('avatarFallback')
].filter(Boolean);

avatarInput?.addEventListener('change', () => {
    const file = avatarInput.files?.[0];
    if (!file) return;

    const url = URL.createObjectURL(file);

    previewTargets.forEach((image) => {
        image.src = url;
        image.classList.remove('hidden');
    });

    fallbackTargets.forEach((fallback) => fallback.classList.add('hidden'));
});

const logoutModal = document.getElementById('logoutConfirmModal');
document.getElementById('openLogoutConfirm')?.addEventListener('click', () => {
    logoutModal?.classList.remove('hidden');
    logoutModal?.classList.add('flex');
});

document.getElementById('cancelLogoutConfirm')?.addEventListener('click', () => {
    logoutModal?.classList.add('hidden');
    logoutModal?.classList.remove('flex');
});

document.getElementById('confirmLogoutSubmit')?.addEventListener('click', () => {
    document.getElementById('logoutForm')?.submit();
});
</script>
@endsection
