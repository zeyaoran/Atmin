@extends('layouts.admin')

@section('content')
<section class="space-y-8">

    {{-- HEADER --}}
    <div class="glass rounded-[22px] p-4 lg:p-5">
        <div class="section-compact flex-col items-start lg:flex-row lg:items-center">

            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Concerts</p>

                <h1 class="mt-1.5 text-xl lg:text-[1.45rem] font-semibold">
                    Manage events and all concerts
                </h1>

                <p class="mt-1.5 max-w-2xl text-[13px] text-slate-400">
                    Event data is used for Upcoming Events, All Concerts, and artist details.
                </p>
            </div>

            <a href="{{ route('admin.events.create') }}" 
               class="btn-primary smooth text-sm">
                Add Event
            </a>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="glass rounded-[22px] p-4">
        <form method="GET" action="{{ route('admin.events.index') }}" 
              class="grid grid-cols-1 gap-3 md:grid-cols-[1fr_auto]">

            <div>
                <label class="mb-1.5 block text-[13px] text-slate-300">Search</label>
                <input type="text" 
                       name="search" 
                       value="{{ $search ?? '' }}" 
                       placeholder="Search artist / event..."
                       class="field py-3 text-sm">
            </div>

            <div class="flex items-end gap-3">
                <button class="btn-primary smooth text-sm w-full md:w-auto">
                    Search
                </button>

                @if(filled($search ?? null))
                    <a href="{{ route('admin.events.index') }}" 
                       class="btn-secondary smooth text-sm w-full md:w-auto">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- TABLE --}}
    <div class="glass rounded-[24px] p-4 lg:p-5">
        <div class="overflow-hidden rounded-[22px] border border-white/10">
            <div class="overflow-x-auto">

                <table class="min-w-[1000px] w-full text-sm">

                    {{-- HEAD --}}
                    <thead class="bg-white/5 text-left text-slate-300">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Image</th>
                            <th class="px-4 py-3">Artist</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Location</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Tag</th>
                            <th class="px-4 py-3">Price</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    {{-- BODY --}}
                    <tbody class="divide-y divide-white/10">
                        @forelse($events as $event)
                            <tr class="bg-white/[0.03] align-top">

                                <td class="px-4 py-3.5 text-slate-300">
                                    {{ $event->id }}
                                </td>

                                <td class="px-4 py-3.5">
                                    <div class="h-14 w-24 overflow-hidden rounded-xl bg-slate-900">
                                        @if($event->image_url)
                                            <img src="{{ $event->image_url }}" 
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-[11px] text-slate-500">
                                                No Image
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-4 py-3.5">
                                    <p class="font-medium text-white">
                                        {{ optional($event->artist)->name ?: '-' }}
                                    </p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        {{ $event->title }}
                                    </p>
                                </td>

                                <td class="px-4 py-3.5 text-slate-300">
                                    {{ $event->category ?: '-' }}
                                </td>

                                <td class="px-4 py-3.5 text-slate-300">
                                    {{ $event->city }}, {{ $event->country }}
                                </td>

                                <td class="px-4 py-3.5 text-slate-300">
                                    {{ optional($event->date)->format('d M Y') ?: '-' }}
                                </td>

                                <td class="px-4 py-3.5">
                                    <span class="rounded-full bg-cyan-400/10 px-2.5 py-1 text-xs text-cyan-100">
                                        {{ $event->status ?: '-' }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 text-cyan-100">
                                    Rp {{ number_format((float) $event->price, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3.5">
                                    <div class="flex justify-end gap-2">

                                        <a href="{{ route('admin.events.show', $event->id) }}" 
                                           class="btn-secondary smooth px-4 py-2 text-sm">
                                            Details
                                        </a>

                                        <a href="{{ route('admin.events.edit', $event->id) }}" 
                                           class="btn-secondary smooth px-4 py-2 text-sm">
                                            Edit
                                        </a>

                                        <form method="POST" 
                                              action="{{ route('admin.events.destroy', $event->id) }}" 
                                              onsubmit="return confirm('Delete this event?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn-secondary smooth border-pink-300/20 
                                                           bg-pink-400/10 px-4 py-2 text-sm text-pink-100">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-10 text-center text-slate-400">
                                    No events yet.
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
