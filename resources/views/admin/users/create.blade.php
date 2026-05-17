@extends('layouts.admin')

@section('content')
<section class="max-w-5xl mx-auto">
    <div class="glass rounded-[24px] overflow-hidden">
        <div class="grid lg:grid-cols-[1.05fr_1.35fr]">
            <div class="border-b border-white/10 bg-gradient-to-br from-fuchsia-500/12 via-transparent to-pink-500/10 p-6 lg:border-b-0 lg:border-r lg:p-7">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Create User</p>
                <h1 class="mt-2 text-xl lg:text-[1.45rem] font-semibold">Create a new user</h1>
                <p class="mt-2 text-sm text-slate-400">Useful for manually creating accounts from the admin dashboard.</p>

                <div class="mt-6 space-y-5">
                    <div class="panel-soft rounded-[22px] p-4">
                        <p class="text-sm font-medium">Quick tips</p>
                        <p class="mt-2 text-sm text-slate-400">Use a unique email and at least 8 password characters so the account can log in through the API.</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary smooth">Back to user list</a>
                </div>
            </div>

            <div class="p-6 lg:p-7">
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-400/30 bg-rose-400/10 p-4 text-sm text-rose-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm text-slate-300">User Name</label>
                        <input name="name" value="{{ old('name') }}" placeholder="Example: Budi Santoso" class="field">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="budi@mail.com" class="field">
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Password</label>
                            <input type="password" name="password" placeholder="At least 8 characters" class="field">
                        </div>
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Confirm Password</label>
                            <input type="password" name="password_confirmation" placeholder="Repeat password" class="field">
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 pt-4 sm:flex-row">
                        <button class="btn-primary smooth">Save User</button>
                        <a href="{{ route('admin.users.index') }}" class="btn-secondary smooth">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
