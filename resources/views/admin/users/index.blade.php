@extends('layouts.admin')

@section('content')
<section class="space-y-8">

    <!-- HEADER -->
    <div class="glass rounded-[22px] p-4 lg:p-5">
        <div class="section-compact flex-col items-start lg:flex-row lg:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">User Management</p>
                <h1 class="mt-1.5 text-xl lg:text-[1.45rem] font-semibold">Create and manage users</h1>
                <p class="mt-1.5 max-w-xl text-[13px] text-slate-400">
                    A simple admin page for adding and managing user accounts.
                </p>
            </div>

            <a href="{{ route('admin.users.create') }}" class="btn-primary smooth text-sm">
                Add User
            </a>
        </div>
    </div>

    <!-- SEARCH -->
    <div class="glass rounded-[22px] p-4 lg:p-5">
        <form method="GET" action="{{ route('admin.users.index') }}"
              class="grid grid-cols-1 xl:grid-cols-[1fr_auto] gap-3">

            <div>
                <label class="mb-1.5 block text-[13px] text-slate-300">Search</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search user name or email..."
                    class="field py-3 text-sm"
                >
            </div>

            <div class="flex items-end gap-3">
                <button class="btn-primary smooth w-full xl:w-auto text-sm">Search</button>

                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}"
                       class="btn-secondary smooth w-full xl:w-auto text-sm">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- TABLE -->
    <div class="glass rounded-[24px] p-4 lg:p-5">
        <div class="overflow-hidden rounded-[22px] border border-white/10">
            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">
                    <thead class="bg-white/5 text-left text-slate-300">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Created</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">
                        @forelse($users as $user)
                            <tr class="bg-white/[0.03]">
                                <td class="px-4 py-4 text-slate-300">{{ $user->id }}</td>
                                <td class="px-4 py-4 font-medium text-white">{{ $user->name }}</td>
                                <td class="px-4 py-4 text-slate-300">{{ $user->email }}</td>

                                <td class="px-4 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-medium
                                        {{ $user->email_verified_at
                                            ? 'bg-emerald-400/10 text-emerald-200'
                                            : 'bg-amber-400/10 text-amber-200' }}">
                                        {{ $user->email_verified_at ? 'Verified' : 'Unverified' }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-slate-400">
                                    {{ optional($user->created_at)->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="btn-secondary smooth px-4 py-2 text-sm">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.users.destroy', $user) }}"
                                              onsubmit="return confirm('Delete this user?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn-secondary smooth border-pink-300/20 bg-pink-400/10 px-4 py-2 text-sm text-pink-100">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-slate-400">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>
        </div>
    </div>

</section>
@endsection
