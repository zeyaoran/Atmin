<!DOCTYPE html>
<html>
<head>

    <title>Admin Panel</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

</head>

<body class="bg-[#0b0b12] text-white">

@if(session('success'))
    <div id="flashToast"
         class="fixed right-5 top-5 z-[100] max-w-sm rounded-2xl border border-emerald-300/20 bg-emerald-500/15 px-5 py-4 text-sm text-emerald-50 shadow-2xl shadow-emerald-950/30 backdrop-blur-xl">
        <div class="flex items-center gap-3">
            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-300/15">
                <i class="ri-check-line text-lg"></i>
            </span>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    </div>
@endif

<!-- OVERLAY -->
<div id="sidebarOverlay"
     class="fixed inset-0 z-40 hidden bg-black/60 backdrop-blur-sm lg:hidden"></div>

<div class="lg:grid lg:grid-cols-[280px_1fr]">

    <!-- MOBILE TOPBAR -->
    <div class="sticky top-0 z-30 flex items-center justify-between border-b border-white/10 bg-black/40 px-4 py-4 backdrop-blur-xl lg:hidden">

        <h2 class="text-lg font-bold">Concert Control</h2>

        <button id="openSidebar"
                class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/5">
            <i class="ri-menu-3-line text-xl"></i>
        </button>

    </div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
           class="fixed left-0 top-0 z-50 h-screen w-[280px]
                  -translate-x-full lg:translate-x-0
                  transition-transform duration-300
                  bg-[#0f0f18] p-5 lg:sticky">

        <div class="flex min-h-full flex-col">

            <!-- HEADER -->
            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">

                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">
                    Admin Panel
                </p>

                <h2 class="mt-2 text-2xl font-bold">
                    Concert Control
                </h2>

                <p class="mt-2 text-xs text-slate-400">
                    Ticketing management system
                </p>

                <button id="closeSidebar"
                        class="mt-4 flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-white/5 lg:hidden">
                    <i class="ri-close-line text-lg"></i>
                </button>

            </div>

            <!-- NAV -->
            <nav class="mt-6 flex-1 space-y-2">

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-white/5 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10' : '' }}">
                    <i class="ri-dashboard-line"></i> Dashboard
                </a>

                <a href="{{ route('admin.events.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-white/5 {{ request()->routeIs('admin.events.*') ? 'bg-white/10' : '' }}">
                    <i class="ri-calendar-event-line"></i> Events
                </a>

                <a href="{{ route('admin.artists.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-white/5 {{ request()->routeIs('admin.artists.*') ? 'bg-white/10' : '' }}">
                    <i class="ri-mic-line"></i> Artists
                </a>

                <a href="{{ route('admin.tickets.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-white/5 {{ request()->routeIs('admin.tickets.*') ? 'bg-white/10' : '' }}">
                    <i class="ri-ticket-2-line"></i> Tickets
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-white/5 {{ request()->routeIs('admin.users.*') ? 'bg-white/10' : '' }}">
                    <i class="ri-team-line"></i> Users
                </a>

                <a href="{{ route('admin.transactions.index') }}"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-white/5 {{ request()->routeIs('admin.transactions.*') ? 'bg-white/10' : '' }}">
                    <i class="ri-bank-card-line"></i> Transactions
                </a>

                <a href="{{ route('admin.react.open') }}"
                   target="_blank"
                   rel="noopener"
                   class="flex items-center gap-3 rounded-xl px-4 py-3 hover:bg-white/5">
                    <i class="ri-reactjs-line"></i> Open React
                </a>

            </nav>

            <!-- MINI PROFILE (REAL IMAGE FIX) -->
            <a href="{{ route('admin.profile') }}"
               class="mt-6 flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 p-4 hover:bg-white/10 transition">

                <!-- AVATAR -->
                <div class="h-11 w-11 rounded-full overflow-hidden bg-white/10 flex items-center justify-center">

                    @if(auth('admin')->user()->image_url)
                        @php
                            $adminImageUrl = auth('admin')->user()->image_url;
                            $adminImageVersion = optional(auth('admin')->user()->updated_at)->timestamp;
                        @endphp
                        <img src="{{ $adminImageUrl }}{{ str_contains($adminImageUrl, '?') ? '&' : '?' }}v={{ $adminImageVersion }}"
                             class="h-full w-full object-cover"
                             alt="profile">
                    @else
                        <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-fuchsia-500 to-pink-500 font-bold text-sm">
                            {{ strtoupper(substr(auth('admin')->user()->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif

                </div>

                <!-- INFO -->
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-white truncate">
                        {{ auth('admin')->user()->name ?? 'Admin' }}
                    </p>

                    <p class="text-xs text-slate-400 truncate">
                        {{ auth('admin')->user()->email ?? 'Super Administrator' }}
                    </p>
                </div>

            </a>

        </div>

    </aside>

    <!-- CONTENT -->
    <main class="p-4 lg:p-8">
        @yield('content')
    </main>

</div>

<!-- SCRIPT MOBILE FIX -->
<script>

const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');

document.getElementById('openSidebar')?.addEventListener('click', () => {
    sidebar.classList.remove('-translate-x-full');
    overlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
});

document.getElementById('closeSidebar')?.addEventListener('click', closeSidebar);
overlay?.addEventListener('click', closeSidebar);

function closeSidebar() {
    sidebar.classList.add('-translate-x-full');
    overlay.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
    } else {
        sidebar.classList.add('-translate-x-full');
    }
});

const flashToast = document.getElementById('flashToast');
if (flashToast) {
    window.setTimeout(() => {
        flashToast.style.transition = 'opacity 250ms ease, transform 250ms ease';
        flashToast.style.opacity = '0';
        flashToast.style.transform = 'translateY(-8px)';
        window.setTimeout(() => flashToast.remove(), 300);
    }, 2600);
}

</script>

</body>
</html>
