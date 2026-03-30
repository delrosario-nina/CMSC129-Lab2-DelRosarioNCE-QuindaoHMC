@extends('layouts.app')

@section('title', 'Recipe Graveyard')

@section('content')
    <div class="flex flex-col items-start mb-8">
        <a href="{{ route('recipes.index') }}"
           class="text-gray-500 hover:text-gray-600 mb-4">← Back to diary</a>
        <h1 class="text-3xl font-bold text-gray-800">Recipe Graveyard</h1>
    </div>

    @if($recipes->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <p class="text-xl">Trash is empty! *^____^*</p>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($recipes as $recipe)
                <div x-data="{ showRestore: false, showDelete: false }"
                     class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col opacity-80">

                    {{-- Image --}}
                    @if($recipe->image_path)
                        <div class="relative">
                            <img src="{{ Storage::url($recipe->image_path) }}"
                                 alt="{{ $recipe->title }}"
                                 class="w-full h-36 object-cover grayscale">
                            <div class="absolute inset-0 bg-gray-900/10"></div>
                        </div>
                    @else
                        <div class="w-full h-36 bg-gray-100 flex items-center justify-center grayscale">
                            <span class="text-5xl">🍽️</span>
                        </div>
                    @endif

                    {{-- Card body --}}
                    <div class="p-3 flex flex-col flex-1">

                        {{-- Category chips --}}
                        @if($recipe->categories->isNotEmpty())
                            @php
                                $chipColors = [
                                    'complexity' => 'background:#fde8e8; color:#c0392b;',
                                    'time'       => 'background:#ede8fd; color:#6c3ec0;',
                                    'type'       => 'background:#e8fde8; color:#278c27;',
                                    'protein'    => 'background:#e8f0fd; color:#2756c0;',
                                    'status'     => 'background:#fdf5e8; color:#b07d1a;',
                                ];
                                $visibleCats = $recipe->categories->take(2);
                                $extraCount  = $recipe->categories->count() - 2;
                            @endphp
                            <div class="flex flex-wrap gap-1 mb-2">
                                @foreach($visibleCats as $cat)
                                    @php $chipStyle = $chipColors[$cat->type] ?? 'background:#f0f0f0; color:#555;'; @endphp
                                    <span style="{{ $chipStyle }} border-radius:999px; padding:0.2rem 0.55rem; font-size:0.7rem; font-weight:600;">
                                        {{ $cat->name }}
                                    </span>
                                @endforeach
                                @if($extraCount > 0)
                                    <span style="background:#f0f0f0; color:#888; border-radius:999px; padding:0.2rem 0.55rem; font-size:0.7rem; font-weight:600;">
                                        +{{ $extraCount }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        {{-- Title & meta --}}
                        <h3 class="font-bold text-base text-gray-700 mb-0.5 leading-tight">{{ $recipe->title }}</h3>
                        <p class="text-gray-400 text-xs mb-3 line-clamp-2">{{ $recipe->description }}</p>
                        <p class="text-gray-300 text-xs mb-3">Deleted {{ $recipe->deleted_at->diffForHumans() }}</p>

                        {{-- Actions --}}
                        <div class="flex gap-1.5 mt-auto">
                            <button type="button" @click="showRestore = true"
                                class="flex-1 bg-green-500 text-white text-xs py-1.5 rounded-lg font-semibold hover:bg-green-600 transition">
                                Restore
                            </button>
                            <button type="button" @click="showDelete = true"
                                class="border border-red-300 text-red-400 text-xs px-2 py-1.5 rounded-lg font-semibold hover:bg-red-50 transition">
                                🗑
                            </button>
                        </div>
                    </div>

                    {{-- Restore modal --}}
                    <template x-if="showRestore">
                        <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; z-index:9999;"
                             @click.self="showRestore = false" @keydown.escape.window="showRestore = false">
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

                    {{-- Force delete modal --}}
                    <template x-if="showDelete">
                        <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; z-index:9999;"
                             @click.self="showDelete = false" @keydown.escape.window="showDelete = false">
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
