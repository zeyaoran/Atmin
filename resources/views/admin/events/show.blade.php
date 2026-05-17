@extends('layouts.admin')

@section('content')
<section class="space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Event Detail</p>
            <h1 class="mt-2 text-xl lg:text-[1.45rem] font-semibold">{{ $event->title }}</h1>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn-primary smooth">Edit Event</a>
            <a href="{{ route('admin.events.index') }}" class="btn-secondary smooth">Back</a>
        </div>
    </div>

    <div class="glass overflow-hidden rounded-[20px]">
        <div class="grid lg:grid-cols-[1.2fr_0.8fr]">
            <div class="min-h-[240px] bg-slate-950">
                @if($event->image)
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="w-full h-full min-h-[240px] object-cover">
                @else
                    <div class="flex h-full min-h-[240px] items-center justify-center text-sm text-slate-500">Event poster is not available yet</div>
                @endif
            </div>

            <div class="p-4 lg:p-5">
                <div class="grid gap-3.5">
                    <div class="panel-soft smooth rounded-[18px] p-3.5">
                        <p class="text-xs text-slate-400">Artist</p>
                        @if($event->artist)
                            <a href="{{ route('admin.artists.show', $event->artist->id) }}"
                               class="mt-1.5 inline-flex items-center gap-2 text-base font-medium text-white hover:text-fuchsia-200">
                                {{ $event->artist->name }}
                                <i class="ri-arrow-right-up-line text-sm"></i>
                            </a>
                        @else
                            <p class="mt-1.5 text-base font-medium">Not connected yet</p>
                        @endif
                    </div>

                    <div class="panel-soft smooth rounded-[18px] p-3.5">
                        <p class="text-xs text-slate-400">Category / Tag</p>
                        <p class="mt-1.5 text-base font-medium">{{ $event->category ?: '-' }} / {{ $event->status ?: '-' }}</p>
                    </div>

                    <div class="panel-soft smooth rounded-[18px] p-3.5">
                        <p class="text-xs text-slate-400">Location</p>
                        <p class="mt-1.5 text-base font-medium">
                            {{ $event->city }}, {{ $event->country }}
                        </p>
                    </div>

                    <div class="panel-soft smooth rounded-[18px] p-3.5">
                        <p class="text-xs text-slate-400">Event Date</p>
                        <p class="mt-1.5 text-base font-medium">{{ optional($event->date)->format('d M Y, H:i') ?: 'Not filled yet' }}</p>
                    </div>

                    <div class="rounded-[18px] border border-fuchsia-300/20 bg-gradient-to-br from-fuchsia-500/20 to-pink-500/20 p-3.5">
                        <p class="text-xs text-fuchsia-100/80">Ticket Price</p>
                        <p class="mt-1.5 text-xl font-semibold text-fuchsia-100">
                            {{ ($event->price !== null && $event->price !== '') ? 'Rp ' . number_format((float) $event->price, 0, ',', '.') : 'Not set yet' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="glass rounded-[24px] p-4 lg:p-5">
        <div class="flex flex-col gap-3 border-b border-white/10 pb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Available Tickets</p>
                <h2 class="mt-1 text-lg font-semibold text-white">Tickets for this event</h2>
            </div>

            <a href="{{ route('admin.tickets.index', ['event' => $event->id]) }}"
               class="btn-secondary smooth text-sm">
                View in Tickets
            </a>
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($event->tickets as $ticket)
                <a href="{{ route('admin.tickets.edit', $ticket->id) }}"
                   class="group rounded-[20px] border border-white/10 bg-white/[0.04] p-4 transition hover:-translate-y-1 hover:border-fuchsia-300/30 hover:bg-white/[0.07]">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Ticket</p>
                            <h3 class="mt-1 text-lg font-semibold text-white">{{ $ticket->category }}</h3>
                        </div>

                        <span class="rounded-full px-3 py-1 text-xs font-medium {{ $ticket->stock > 0 ? 'bg-emerald-400/10 text-emerald-200' : 'bg-rose-400/10 text-rose-200' }}">
                            {{ $ticket->stock > 0 ? 'Available' : 'Sold Out' }}
                        </span>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl border border-white/10 bg-black/20 p-3">
                            <p class="text-xs text-slate-400">Price</p>
                            <p class="mt-1 text-sm font-semibold text-fuchsia-100">
                                Rp {{ number_format((float) $ticket->price, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-black/20 p-3">
                            <p class="text-xs text-slate-400">Stock</p>
                            <p class="mt-1 text-sm font-semibold text-white">{{ $ticket->stock }}</p>
                        </div>
                    </div>

                    <p class="mt-4 inline-flex items-center gap-2 text-xs font-medium text-slate-400 group-hover:text-fuchsia-100">
                        Edit ticket
                        <i class="ri-arrow-right-up-line"></i>
                    </p>
                </a>
            @empty
                <div class="rounded-[20px] border border-white/10 bg-white/[0.04] p-5 text-sm text-slate-400 md:col-span-2 xl:col-span-3">
                    This event does not have tickets yet.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
