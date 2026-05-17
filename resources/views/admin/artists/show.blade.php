@extends('layouts.admin')

@section('content')
<section class="space-y-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Artist Detail</p>
            <h1 class="mt-2 text-xl lg:text-[1.45rem] font-semibold">{{ $artist->name }}</h1>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.artists.edit', $artist->id) }}" class="btn-primary smooth">Edit Artist</a>
            <a href="{{ route('admin.artists.index') }}" class="btn-secondary smooth">Back</a>
        </div>
    </div>

    <div class="glass overflow-hidden rounded-[20px]">
        <div class="grid lg:grid-cols-[1.2fr_0.8fr]">
            <div class="min-h-[240px] bg-slate-950">
                @if($artist->image)
                    <img src="{{ asset('storage/' . $artist->image) }}" 
                        alt="{{ $artist->name }}" 
                        class="w-full h-full min-h-[240px] object-cover">
                @else
                    <div class="flex h-full min-h-[240px] items-center justify-center text-sm text-slate-500">
                        Artist photo is not available yet
                    </div>
                @endif
            </div>

            <div class="p-4 lg:p-5">
                <div class="grid gap-3.5">
                    <div class="panel-soft smooth rounded-[18px] p-3.5">
                        <p class="text-xs text-slate-400">Artist Name</p>
                        <p class="mt-1.5 text-base font-medium">{{ $artist->name }}</p>
                    </div>

                    <div class="rounded-[18px] border border-fuchsia-300/20 bg-gradient-to-br from-fuchsia-500/20 to-pink-500/20 p-3.5">
                        <p class="text-xs text-fuchsia-100/80">Description</p>
                        <p class="mt-1.5 text-xs leading-6 text-fuchsia-50">{{ $artist->description ?: 'No artist description yet.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="glass rounded-[24px] p-4 lg:p-5">
        <div class="flex flex-col gap-3 border-b border-white/10 pb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Available Events</p>
                <h2 class="mt-1 text-lg font-semibold text-white">Events by {{ $artist->name }}</h2>
            </div>

            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-300">
                {{ $artist->events->count() }} event
            </span>
        </div>

        <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($artist->events as $event)
                <a href="{{ route('admin.events.show', $event->id) }}"
                   class="group overflow-hidden rounded-[20px] border border-white/10 bg-white/[0.04] transition hover:-translate-y-1 hover:border-fuchsia-300/30 hover:bg-white/[0.07]">
                    <div class="h-36 bg-slate-950">
                        @if($event->image_url)
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        @else
                            <div class="flex h-full items-center justify-center text-xs text-slate-500">
                                Poster is not available yet
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="line-clamp-2 text-sm font-semibold text-white">
                                {{ $event->title }}
                            </h3>
                            <span class="shrink-0 rounded-full bg-fuchsia-500/15 px-2.5 py-1 text-[11px] text-fuchsia-100">
                                {{ $event->status ?: 'EVENT' }}
                            </span>
                        </div>

                        <div class="mt-3 space-y-2 text-xs text-slate-400">
                            <p class="flex items-center gap-2">
                                <i class="ri-map-pin-line text-fuchsia-200"></i>
                                {{ $event->city }}, {{ $event->country }}
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="ri-calendar-line text-fuchsia-200"></i>
                                {{ optional($event->date)->format('d M Y, H:i') ?: 'Date is not filled yet' }}
                            </p>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-3 border-t border-white/10 pt-3">
                            <p class="text-sm font-semibold text-fuchsia-100">
                                {{ $event->price !== null ? 'Rp ' . number_format((float) $event->price, 0, ',', '.') : 'No price yet' }}
                            </p>
                            <p class="text-xs text-slate-400">
                                {{ $event->tickets_count }} ticket
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="rounded-[20px] border border-white/10 bg-white/[0.04] p-5 text-sm text-slate-400 md:col-span-2 xl:col-span-3">
                    This artist does not have events yet.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
