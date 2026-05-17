@extends('layouts.admin')

@section('content')
<section class="max-w-5xl mx-auto">
    <div class="glass rounded-[24px] overflow-hidden">
        <div class="grid lg:grid-cols-[1.05fr_1.35fr]">

            <div class="border-b border-white/10 bg-gradient-to-br from-fuchsia-500/12 via-transparent to-pink-500/10 p-6 lg:border-b-0 lg:border-r lg:p-7">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Create Ticket</p>

                <h1 class="mt-2 text-xl lg:text-[1.45rem] font-semibold">
                    Add a new ticket category
                </h1>

                <p class="mt-2 text-sm text-slate-400">
                    Tickets only contain an artist, optional event, category, price, and stock.
                </p>

                <div class="mt-6 space-y-5">
                    <div class="panel-soft rounded-[22px] p-4">
                        <p class="text-sm font-medium">Category System</p>
                        <p class="mt-2 text-sm text-slate-400">
                            VIP, CAT1, and CAT2 are used as concert seat tiers.
                        </p>
                    </div>

                    <a href="{{ route('admin.tickets.index') }}" class="btn-secondary smooth">
                        Back to ticket list
                    </a>
                </div>
            </div>

            <div class="p-6 lg:p-7">
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-400/30 bg-rose-400/10 p-4 text-sm text-rose-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.tickets.store') }}" class="space-y-5">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Artist</label>
                            <select name="artist_id" class="field">
                                <option value="">Select artist</option>
                                @foreach($artists as $artist)
                                    <option value="{{ $artist->id }}" @selected(old('artist_id') == $artist->id)>
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
                                    <option value="{{ $event->id }}" @selected(old('event_id') == $event->id)>
                                        {{ $event->title }}
                                        @if($event->artist)
                                            - {{ $event->artist->name }}
                                        @endif
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
                                    <option value="{{ $category }}" @selected(old('category', 'VIP') === $category)>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Price</label>
                            <input type="number" name="price" value="{{ old('price') }}" placeholder="2500000" class="field">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm text-slate-300">Stock</label>
                            <input type="number" name="stock" value="{{ old('stock', 0) }}" placeholder="100" class="field">
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 pt-4 sm:flex-row">
                        <button class="btn-primary smooth">Save Ticket</button>
                        <a href="{{ route('admin.tickets.index') }}" class="btn-secondary smooth">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
