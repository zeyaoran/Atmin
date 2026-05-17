@extends('layouts.admin')

@section('content')
<section class="max-w-5xl mx-auto">
    <div class="glass rounded-[24px] overflow-hidden">
        <div class="grid lg:grid-cols-[1.05fr_1.35fr]">

            <div class="border-b border-white/10 bg-gradient-to-br from-fuchsia-500/12 via-transparent to-pink-500/10 p-6 lg:border-b-0 lg:border-r lg:p-7">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Edit Ticket</p>
                <h1 class="mt-2 text-xl lg:text-[1.45rem] font-semibold">
                    {{ optional($ticket->artist)->name ?: 'Ticket' }}
                </h1>

                <p class="mt-2 text-sm text-slate-400">
                    Update the ticket category, price, and stock.
                </p>

                <div class="mt-6 space-y-5">
                    <div class="panel-soft rounded-[22px] p-4">
                        <p class="text-sm font-medium">Summary</p>
                        <p class="mt-2 text-sm text-slate-400">
                            {{ optional($ticket->artist)->name ?: '-' }}
                            - {{ $ticket->category }}
                            - {{ optional($ticket->event)->title ?: 'Artist only' }}
                        </p>
                    </div>

                    <a href="{{ route('admin.tickets.index') }}" class="btn-secondary smooth">
                        Back
                    </a>
                </div>
            </div>

            <div class="p-6 lg:p-7">
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-400/30 bg-rose-400/10 p-4 text-sm text-rose-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Artist</label>
                            <select name="artist_id" class="field">
                                <option value="">Select artist</option>
                                @foreach($artists as $artist)
                                    <option value="{{ $artist->id }}"
                                        @selected(old('artist_id', $ticket->artist_id) == $artist->id)>
                                        {{ $artist->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Event</label>
                            <select name="event_id" class="field">
                                <option value="">No event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}"
                                        @selected(old('event_id', $ticket->event_id) == $event->id)>
                                        {{ $event->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-5">
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Category</label>
                            <select name="category" class="field">
                                @foreach(['VIP', 'CAT1', 'CAT2'] as $category)
                                    <option value="{{ $category }}"
                                        @selected(old('category', $ticket->category) === $category)>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Price</label>
                            <input type="number" name="price"
                                value="{{ old('price', $ticket->price) }}"
                                class="field">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Stock</label>
                            <input type="number" name="stock"
                                value="{{ old('stock', $ticket->stock) }}"
                                class="field">
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 pt-4 sm:flex-row">
                        <button class="btn-primary smooth">Update Ticket</button>
                        <a href="{{ route('admin.tickets.index') }}" class="btn-secondary smooth">Cancel</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>
@endsection
