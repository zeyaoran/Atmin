@extends('layouts.admin')

@section('content')
<section class="space-y-8">
    <div class="glass rounded-[22px] p-4 lg:p-5">
        <div class="section-compact flex-col items-start lg:flex-row lg:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Artists</p>
                <h1 class="mt-1.5 text-xl lg:text-[1.45rem] font-semibold">Manage artists for the frontend</h1>
                <p class="mt-1.5 max-w-xl text-[13px] text-slate-400">Only the fields used by the artist component are shown: name, photo, and description.</p>
            </div>

            <a href="{{ route('admin.artists.create') }}" class="btn-primary smooth text-sm">
                <span>Add Artist</span>
            </a>
        </div>
    </div>

    <div class="glass rounded-[24px] p-4 lg:p-5">
        <div class="overflow-hidden rounded-[22px] border border-white/10">
            <div class="overflow-x-auto">
                <table class="min-w-[920px] w-full text-sm">
                    <thead class="bg-white/5 text-left text-slate-300">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Photo</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($artists as $artist)
                            <tr class="bg-white/[0.03]">
                                <td class="px-4 py-3.5 text-slate-300">{{ $artist->id }}</td>
                                <td class="px-4 py-3.5">
                                    <div class="h-14 w-14 overflow-hidden rounded-full bg-slate-900">
                                        @if($artist->image)
                                            <img src="{{ asset('storage/' . $artist->image) }}" 
                                                alt="{{ $artist->name }}" 
                                                class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-[11px] text-slate-500">No</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 font-medium text-white">{{ $artist->name }}</td>
                                <td class="px-4 py-3.5 text-slate-300">{{ \Illuminate\Support\Str::limit($artist->description ?: '-', 110) }}</td>
                                <td class="px-4 py-3.5">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.artists.show', $artist->id) }}" class="btn-secondary smooth px-4 py-2 text-sm">Details</a>
                                        <a href="{{ route('admin.artists.edit', $artist->id) }}" class="btn-secondary smooth px-4 py-2 text-sm">Edit</a>
                                        <form method="POST" action="{{ route('admin.artists.destroy', $artist->id) }}" onsubmit="return confirm('Delete this artist?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-secondary smooth border-pink-300/20 bg-pink-400/10 px-4 py-2 text-sm text-pink-100">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center text-slate-400">No artists yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
