@extends('layouts.admin')

@section('content')
<section class="max-w-5xl mx-auto">
    <div class="glass rounded-[24px] overflow-hidden">
        <div class="grid lg:grid-cols-[1.05fr_1.35fr]">
            <div class="p-6 lg:p-7 border-b lg:border-b-0 lg:border-r border-white/10 bg-gradient-to-br from-fuchsia-500/12 via-transparent to-pink-500/10">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Create Artist</p>
                <h1 class="mt-2 text-xl lg:text-[1.45rem] font-semibold">Add a new artist</h1>
                <p class="mt-2 text-sm text-slate-400">Aligned with the artist content used in the frontend project.</p>

                <div class="mt-6 space-y-5">
                    <div class="panel-soft rounded-[22px] p-4">
                        <p class="text-sm font-medium">Active fields</p>
                        <p class="mt-2 text-sm text-slate-400">Artist name, artist photo, and artist description.</p>
                    </div>
                    <a href="{{ route('admin.artists.index') }}" class="btn-secondary smooth">Back to artist list</a>
                </div>
            </div>

            <div class="p-6 lg:p-7">
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-400/30 bg-rose-400/10 p-4 text-sm text-rose-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.artists.store') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Artist Name</label>
                        <input name="name" value="{{ old('name') }}" placeholder="Example: BLACKPINK" class="field">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Description</label>
                        <textarea name="description" rows="5" placeholder="Short artist description..." class="field">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Artist Photo</label>
                        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="field file:mr-4 file:rounded-full file:border-0 file:bg-orange-500/20 file:px-4 file:py-2 file:text-sm file:font-medium file:text-orange-100">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button class="btn-primary smooth">Save Artist</button>
                        <a href="{{ route('admin.artists.index') }}" class="btn-secondary smooth">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
