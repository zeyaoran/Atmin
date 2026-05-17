<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background:
                radial-gradient(circle at 20% 10%, rgba(168, 85, 247, 0.28), transparent 28%),
                radial-gradient(circle at 80% 20%, rgba(236, 72, 153, 0.24), transparent 28%),
                radial-gradient(circle at 50% 100%, rgba(59, 130, 246, 0.12), transparent 30%),
                #09090f;
        }

        .login-field:focus {
            border-color: rgba(236, 72, 153, 0.7);
            box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.14);
        }
    </style>
</head>

<body class="min-h-screen overflow-hidden text-white">
    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="rounded-[28px] border border-white/10 bg-white/[0.07] p-6 shadow-2xl shadow-fuchsia-950/30 backdrop-blur-2xl">

                <div class="mb-6 text-center">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-fuchsia-500 to-pink-500 text-xl font-black shadow-lg shadow-fuchsia-500/25">
                        A
                    </div>

                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Admin Access</p>
                    <h1 class="mt-2 text-2xl font-semibold text-white">Login Dashboard</h1>
                    <p class="mt-1 text-sm text-slate-400">Sign in with Google, email, or admin name</p>
                </div>

                @if(session('error'))
                    <div class="mb-4 rounded-xl border border-red-400/20 bg-red-500/10 p-3 text-sm text-red-200">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 rounded-xl border border-amber-400/20 bg-amber-500/10 p-3 text-sm text-amber-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-xs text-slate-400">Name or Email</label>
                        <input
                            type="text"
                            name="login"
                            value="{{ old('login') }}"
                            required
                            class="login-field mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none transition placeholder:text-slate-600"
                            placeholder="admin@email.com or admin name"
                        >
                    </div>

                    <div>
                        <label class="text-xs text-slate-400">Password</label>
                        <input
                            type="password"
                            name="password"
                            required
                            class="login-field mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-white outline-none transition placeholder:text-slate-600"
                            placeholder="Password"
                        >
                    </div>

                    <label class="flex items-center gap-2 text-xs text-slate-400">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-white/10 bg-white/5 accent-pink-500">
                        Remember me
                    </label>

                    <button
                        type="submit"
                        class="w-full rounded-2xl border border-fuchsia-300/20 bg-gradient-to-r from-fuchsia-500 to-pink-500 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-fuchsia-500/20 transition hover:brightness-110"
                    >
                        Login
                    </button>
                </form>

                <div class="my-5 flex items-center gap-3 text-xs uppercase tracking-[0.25em] text-slate-500">
                    <span class="h-px flex-1 bg-white/10"></span>
                    or
                    <span class="h-px flex-1 bg-white/10"></span>
                </div>

                @if($googleConfigured)
                    <a href="{{ route('admin.login.google') }}"
                       class="flex w-full items-center justify-center gap-3 rounded-2xl border border-white/10 bg-white px-4 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06L5.84 9.9C6.71 7.31 9.14 5.38 12 5.38z"/>
                        </svg>
                        Login with Google
                    </a>
                @else
                    <button
                        type="button"
                        disabled
                        class="flex w-full cursor-not-allowed items-center justify-center gap-3 rounded-2xl border border-white/10 bg-white/60 px-4 py-3 text-sm font-semibold text-slate-500"
                    >
                        <svg class="h-5 w-5 grayscale" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06L5.84 9.9C6.71 7.31 9.14 5.38 12 5.38z"/>
                        </svg>
                        Login with Google
                    </button>
                    <p class="mt-2 text-center text-xs text-slate-500">
                        Google login is not active yet
                    </p>
                @endif
            </div>

            <p class="mt-6 text-center text-xs text-slate-500">
                Admin Panel
            </p>
        </div>
    </main>
</body>
</html>
