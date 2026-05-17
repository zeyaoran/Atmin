@extends('layouts.admin')

@section('content')
<section class="space-y-8">

    <div class="glass rounded-[22px] p-4 lg:p-5">
        <div class="section-compact flex-col items-start lg:flex-row lg:items-center">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Tickets</p>
                <h1 class="mt-1.5 text-xl lg:text-[1.45rem] font-semibold">Manage checkout ticket categories</h1>
                <p class="mt-1.5 max-w-2xl text-[13px] text-slate-400">
                    Aligned with Checkout.jsx, so tickets only need an artist, optional event, category, price, and stock.
                </p>
            </div>

            <a href="{{ route('admin.tickets.create') }}" class="btn-primary smooth text-sm">
                <span>Add Ticket</span>
            </a>
        </div>
    </div>

    <div class="glass rounded-[22px] p-4">
        <form method="GET" action="{{ route('admin.tickets.index') }}"
              class="grid grid-cols-1 gap-3 xl:grid-cols-[1.2fr_0.8fr_0.8fr_auto]">

            <div>
                <label class="mb-1.5 block text-[13px] text-slate-300">Search</label>
                <input type="text" name="search" value="{{ $search }}"
                       placeholder="Search category, artist, or event..."
                       class="field py-3 text-sm">
            </div>

            <div>
                <label class="mb-1.5 block text-[13px] text-slate-300">Artist</label>
                <select name="artist" class="field py-3 text-sm">
                    <option value="">All artists</option>
                    @foreach($artists as $artist)
                        <option value="{{ $artist->id }}" @selected((string) $artistId === (string) $artist->id)>
                            {{ $artist->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-[13px] text-slate-300">Event</label>
                <select name="event" class="field py-3 text-sm">
                    <option value="">All events</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" @selected((string) $eventId === (string) $event->id)>
                            {{ $event->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-3">
                <button class="btn-primary smooth w-full text-sm xl:w-auto">Search</button>

                @if(filled($search) || filled($artistId) || filled($eventId))
                    <a href="{{ route('admin.tickets.index') }}"
                       class="btn-secondary smooth w-full text-sm xl:w-auto">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="glass rounded-[24px] p-4 lg:p-5">
        <div class="overflow-hidden rounded-[22px] border border-white/10">
            <div class="overflow-x-auto">

                <table class="min-w-[1000px] w-full text-sm">
                    <thead class="bg-white/5 text-left text-slate-300">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Artist</th>
                            <th class="px-4 py-3">Event</th>
                            <th class="px-4 py-3">Category</th>
                            <th class="px-4 py-3">Price</th>
                            <th class="px-4 py-3">Stock</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-white/10">
                        @forelse($tickets as $ticket)
                            <tr class="bg-white/[0.03]">
                                <td class="px-4 py-3.5 text-slate-300">{{ $ticket->id }}</td>

                                <td class="px-4 py-3.5 text-slate-200">
                                    {{ optional($ticket->artist)->name ?: '-' }}
                                </td>

                                <td class="px-4 py-3.5 text-slate-300">
                                    {{ optional($ticket->event)->title ?: 'Artist only' }}
                                </td>

                                <td class="px-4 py-3.5">
                                    <span class="rounded-full bg-cyan-400/10 px-2.5 py-1 text-xs text-cyan-100">
                                        {{ $ticket->category }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 text-cyan-100">
                                    Rp {{ number_format((float) $ticket->price, 0, ',', '.') }}
                                </td>

                                {{-- STOCK --}}
                                <td class="px-4 py-3.5">
                                    @if($ticket->stock > 10)
                                        <span class="text-emerald-300">{{ $ticket->stock }}</span>
                                    @elseif($ticket->stock > 0)
                                        <span class="text-yellow-300">{{ $ticket->stock }}</span>
                                    @else
                                        <span class="text-pink-300">Sold out</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3.5">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.tickets.edit', $ticket) }}"
                                           class="btn-secondary smooth px-4 py-2 text-sm">
                                            Edit
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.tickets.destroy', $ticket) }}"
                                              onsubmit="return confirm('Delete this ticket?')">
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
                                <td colspan="7" class="px-4 py-10 text-center text-slate-400">
                                    No ticket categories yet.
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
