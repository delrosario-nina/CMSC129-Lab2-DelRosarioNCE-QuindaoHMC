@extends('layouts.app')

@section('title', 'Trash')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">🗑 Trash</h1>
        <a href="{{ route('recipes.index') }}"
           class="text-amber-500 hover:text-amber-600">← Back to Recipes</a>
    </div>

    @if($recipes->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <p class="text-6xl mb-4">✨</p>
            <p class="text-xl">Trash is empty!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recipes as $recipe)
                <div x-data="{ showRestore: false, showDelete: false }"
                     class="bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden opacity-75">

                    @if($recipe->image_path)
                        <img src="{{ Storage::url($recipe->image_path) }}"
                             alt="{{ $recipe->title }}"
                             class="w-full h-48 object-cover grayscale">
                    @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                            <span class="text-5xl grayscale">🍽️</span>
                        </div>
                    @endif

                    <div class="p-4">
                        <h3 class="font-bold text-lg text-gray-500 mb-1">{{ $recipe->title }}</h3>
                        <p class="text-gray-400 text-xs mb-4">
                            Deleted {{ $recipe->deleted_at->diffForHumans() }}
                        </p>

                        <div class="flex gap-2">
                            {{-- Restore button --}}
                            <button type="button" @click="showRestore = true"
                                class="w-full bg-green-500 text-white text-sm py-2 rounded-lg hover:bg-green-600 transition">
                                ♻️ Restore
                            </button>

                            {{-- Force delete button --}}
                            <button type="button" @click="showDelete = true"
                                class="border border-red-400 text-red-400 text-sm px-3 py-2 rounded-lg hover:bg-red-50 transition">
                                🗑 Delete Forever
                            </button>
                        </div>
                    </div>

                    {{-- Restore confirm modal --}}
                    <template x-if="showRestore">
                        <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; z-index:9999;"
                             @click.self="showRestore = false"
                             @keydown.escape.window="showRestore = false">
                            <div style="background:#fff; border-radius:1rem; border:1px solid #e5e5e5; box-shadow:0 8px 32px rgba(0,0,0,0.18); max-width:420px; width:90%; padding:2rem; text-align:center;" @click.stop>
                                <h3 style="margin:0 0 0.75rem; color:#111; font-size:1.1rem; font-weight:700;">Restore Recipe?</h3>
                                <p style="margin:0 0 1.5rem; color:#444; font-size:0.95rem; line-height:1.5;">
                                    <strong>{{ $recipe->title }}</strong> will be restored to your diary.
                                </p>
                                <div style="display:flex; justify-content:center; gap:0.75rem;">
                                    <form action="{{ route('recipes.restore', $recipe->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            style="background:#22c55e; color:#fff; border:none; border-radius:0.5rem; padding:0.65rem 1.35rem; cursor:pointer; font-size:0.9rem; font-weight:600;">
                                            Yes, restore
                                        </button>
                                    </form>
                                    <button type="button" @click="showRestore = false"
                                        style="background:#f5f5f5; color:#333; border:1px solid #dcdcdc; border-radius:0.5rem; padding:0.65rem 1.35rem; cursor:pointer; font-size:0.9rem; font-weight:600;">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Force delete confirm modal --}}
                    <template x-if="showDelete">
                        <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; z-index:9999;"
                             @click.self="showDelete = false"
                             @keydown.escape.window="showDelete = false">
                            <div style="background:#fff; border-radius:1rem; border:1px solid #e5e5e5; box-shadow:0 8px 32px rgba(0,0,0,0.18); max-width:420px; width:90%; padding:2rem; text-align:center;" @click.stop>
                                <h3 style="margin:0 0 0.75rem; color:#c00; font-size:1.1rem; font-weight:700;">Delete Forever?</h3>
                                <p style="margin:0 0 1.5rem; color:#444; font-size:0.95rem; line-height:1.5;">
                                    <strong>{{ $recipe->title }}</strong> will be permanently deleted. This cannot be undone.
                                </p>
                                <div style="display:flex; justify-content:center; gap:0.75rem;">
                                    <form action="{{ route('recipes.forceDelete', $recipe->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            style="background:#dc2626; color:#fff; border:none; border-radius:0.5rem; padding:0.65rem 1.35rem; cursor:pointer; font-size:0.9rem; font-weight:600;">
                                            Yes, delete forever
                                        </button>
                                    </form>
                                    <button type="button" @click="showDelete = false"
                                        style="background:#f5f5f5; color:#333; border:1px solid #dcdcdc; border-radius:0.5rem; padding:0.65rem 1.35rem; cursor:pointer; font-size:0.9rem; font-weight:600;">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $recipes->links() }}
        </div>
    @endif
@endsection
