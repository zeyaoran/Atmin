@extends('layouts.admin')

@section('content')
<section class="max-w-6xl mx-auto">
    <div class="glass rounded-[24px] overflow-hidden">
        <div class="grid lg:grid-cols-[1.05fr_1.35fr]">

            {{-- LEFT --}}
            <div class="p-6 lg:p-7 border-b lg:border-b-0 lg:border-r border-white/10 
                        bg-gradient-to-br from-violet-500/12 via-transparent to-pink-500/10">

                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Edit Event</p>

                <h1 class="mt-2 text-xl font-semibold">
                    {{ $event->title }}
                </h1>

                <div class="mt-6 panel-soft rounded-[22px] p-4">
                    @if($event->image)
                        <img src="{{ $event->image_url }}" class="w-full h-56 object-cover rounded-2xl">
                    @else
                        <div class="w-full h-56 flex items-center justify-center text-slate-500">
                            No Image
                        </div>
                    @endif

                    <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                        <div class="bg-slate-900/70 p-3 rounded-[18px]">
                            <p class="text-slate-400">Artist</p>
                            <p>{{ optional($event->artist)->name ?: '-' }}</p>
                        </div>

                        <div class="bg-slate-900/70 p-3 rounded-[18px]">
                            <p class="text-slate-400">Location</p>
                            <p>{{ $event->city }}, {{ $event->country }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="p-6 lg:p-7">

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-400/30 bg-rose-400/10 p-4 text-sm text-rose-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.events.update', $event->id) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid md:grid-cols-2 gap-5">
                        <select name="artist_id" class="field">
                            @foreach($artists as $artist)
                                <option value="{{ $artist->id }}" @selected($event->artist_id == $artist->id)>
                                    {{ $artist->name }}
                                </option>
                            @endforeach
                        </select>

                        <select name="category" class="field">
                            @foreach($eventCategories as $category)
                                <option value="{{ $category }}" @selected($event->category == $category)>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input name="title" value="{{ $event->title }}" class="field">

                    <div class="grid md:grid-cols-2 gap-5">
                        <input name="city" value="{{ $event->city }}" class="field">
                        <input name="country" value="{{ $event->country }}" class="field">
                    </div>

                    <div class="grid md:grid-cols-2 gap-5">
                        <input type="date" name="date" value="{{ optional($event->date)->format('Y-m-d') }}" class="field">
                        <input type="number" name="price" value="{{ old('price', $event->price) }}" placeholder="Starting event price" class="field">
                    </div>


                    <select name="status" class="field">
                        @foreach($eventTags as $tag)
                            <option value="{{ $tag }}" @selected($event->status == $tag)>
                                {{ $tag }}
                            </option>
                        @endforeach
                    </select>

                    <input type="file" name="image" class="field">

                    <div class="flex gap-3 pt-4">
                        <button class="btn-primary">Update</button>
                        <a href="{{ route('admin.events.index') }}" class="btn-secondary">Back</a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</section>
@endsection
