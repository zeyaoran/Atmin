@extends('layouts.admin')

@section('content')
<section class="max-w-5xl mx-auto">
    <div class="glass rounded-[24px] overflow-hidden shadow-xl">
        <div class="grid lg:grid-cols-[1.05fr_1.35fr]">

            <div class="p-7 border-b lg:border-b-0 lg:border-r border-white/10
                        bg-gradient-to-br from-fuchsia-500/15 via-transparent to-pink-500/10">

                <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Create Event</p>

                <h1 class="mt-3 text-xl lg:text-[1.5rem] font-semibold">
                    Add a new concert
                </h1>

                <p class="mt-2 text-sm text-slate-400 leading-relaxed">
                    This data will be used across event pages.
                </p>

                <div class="mt-6 space-y-3 text-sm text-slate-400">
                    <p>- Use a clear title</p>
                    <p>- Choose the correct event category</p>
                    <p>- Fill in the city and country correctly</p>
                </div>

                <a href="{{ route('admin.events.index') }}"
                   class="btn-secondary smooth mt-7 inline-block text-sm opacity-80 hover:opacity-100">
                    Back
                </a>
            </div>

            <div class="p-7">

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-400/30 bg-rose-400/10 p-4 text-sm text-rose-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.events.store') }}"
                      enctype="multipart/form-data"
                      class="space-y-6">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-5">
                        <select name="artist_id" class="field">
                            <option value="">Select artist</option>
                            @foreach($artists as $artist)
                                <option value="{{ $artist->id }}">{{ $artist->name }}</option>
                            @endforeach
                        </select>

                        <select name="category" class="field">
                            @foreach($eventCategories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>

                    <input name="title" placeholder="Event Title" class="field">

                    <div class="grid md:grid-cols-2 gap-5">
                        <input name="city" placeholder="City (example: Jakarta)" class="field">
                        <input name="country" placeholder="Country (example: Indonesia)" class="field">
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <input type="date" name="date" class="field dark-date">
                        <input type="number" name="price" value="{{ old('price') }}" placeholder="Starting event price" class="field">
                    </div>

                    <select name="status" class="field">
                        @foreach($eventTags as $tag)
                            <option value="{{ $tag }}">{{ $tag }}</option>
                        @endforeach
                    </select>

                    <input type="file" name="image" class="field">

                    <div class="flex items-center gap-3 pt-2">
                        <button class="btn-primary smooth text-sm px-5 py-2.5">
                            Save
                        </button>

                        <a href="{{ route('admin.events.index') }}" class="text-sm text-slate-400">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</section>

<style>
.dark-date { color-scheme: dark; }
.dark-date::-webkit-calendar-picker-indicator { filter: invert(1); }
</style>
@endsection
