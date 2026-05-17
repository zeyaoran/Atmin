@extends('layouts.admin')

@section('content')
<section class="max-w-5xl mx-auto">
    <div class="glass rounded-[24px] overflow-hidden">
        <div class="grid lg:grid-cols-[1.05fr_1.35fr]">
            <div class="p-6 lg:p-7 border-b lg:border-b-0 lg:border-r border-white/10 bg-gradient-to-br from-violet-500/12 via-transparent to-pink-500/10">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Edit Artist</p>
                <h1 class="mt-2 text-xl lg:text-[1.45rem] font-semibold">{{ $artist->name }}</h1>

                <div class="mt-6 panel-soft smooth rounded-[22px] p-4">
                    @if($artist->image)
                        <img src="{{ asset('storage/' . $artist->image) }}" 
                            alt="{{ $artist->name }}" 
                            class="w-full h-56 object-cover rounded-2xl">
                    @else
                        <div class="w-full h-56 rounded-2xl bg-slate-900 flex items-center justify-center text-slate-500">No artist photo yet</div>
                    @endif
                </div>
            </div>

            <div class="p-6 lg:p-7">
                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-400/30 bg-rose-400/10 p-4 text-sm text-rose-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.artists.update', $artist->id) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Artist Name</label>
                        <input name="name" value="{{ old('name', $artist->name) }}" class="field">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Description</label>
                        <textarea name="description" rows="5" class="field">{{ old('description', $artist->description) }}</textarea>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm text-slate-300">Change Photo</label>
                        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="field file:mr-4 file:rounded-full file:border-0 file:bg-fuchsia-500/20 file:px-4 file:py-2 file:text-sm file:font-medium file:text-fuchsia-100">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button class="btn-primary smooth text-sm">Update Artist</button>
                        <a href="{{ route('admin.artists.show', $artist->id) }}" class="btn-secondary smooth text-sm">View Details</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
