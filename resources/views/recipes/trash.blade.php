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
                <div class="bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden opacity-75">

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
                            {{-- Restore --}}
                            <form action="{{ route('recipes.restore', $recipe->id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="w-full bg-green-500 text-white text-sm py-2 rounded-lg hover:bg-green-600 transition">
                                    ♻️ Restore
                                </button>
                            </form>

                            {{-- Permanent Delete --}}
                            <form action="{{ route('recipes.forceDelete', $recipe->id) }}" method="POST"
                                  onsubmit="return confirm('Permanently delete this recipe? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="border border-red-400 text-red-400 text-sm px-3 py-2 rounded-lg hover:bg-red-50 transition">
                                    🗑 Delete Forever
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $recipes->links() }}
        </div>
    @endif
@endsection
