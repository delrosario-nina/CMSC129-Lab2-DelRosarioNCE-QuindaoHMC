@extends('layouts.app')

@section('title', $recipe->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('recipes.index') }}"
           class="text-amber-500 hover:text-amber-600">← Back to Recipes</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-amber-100">

        {{-- Image --}}
        @if($recipe->image_path)
            <img src="{{ Storage::url($recipe->image_path) }}"
                 alt="{{ $recipe->title }}"
                 class="w-full h-72 object-cover">
        @else
            <div class="w-full h-72 bg-amber-100 flex items-center justify-center">
                <span class="text-8xl">🍽️</span>
            </div>
        @endif

        <div class="p-8">

            {{-- Header --}}
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $recipe->title }}</h1>
                    <p class="text-gray-500">{{ $recipe->description }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('recipes.edit', $recipe) }}"
                       class="border border-amber-500 text-amber-500 px-4 py-2 rounded-lg hover:bg-amber-50 transition">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('recipes.destroy', $recipe) }}" method="POST"
                          onsubmit="return confirm('Move to trash?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="border border-red-400 text-red-400 px-4 py-2 rounded-lg hover:bg-red-50 transition">
                            🗑 Trash
                        </button>
                    </form>
                </div>
            </div>

            {{-- Meta Info --}}
            <div class="grid grid-cols-3 gap-4 bg-amber-50 rounded-xl p-4 mb-8">
                <div class="text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Prep Time</p>
                    <p class="text-xl font-bold text-amber-600">{{ $recipe->prep_time }}<span class="text-sm font-normal">min</span></p>
                </div>
                <div class="text-center border-x border-amber-200">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Cook Time</p>
                    <p class="text-xl font-bold text-amber-600">{{ $recipe->cook_time }}<span class="text-sm font-normal">min</span></p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wide">Difficulty</p>
                    <p class="text-xl font-bold text-amber-600 capitalize">{{ $recipe->difficulty }}</p>
                </div>
            </div>

            {{-- Categories --}}
            @if($recipe->categories->isNotEmpty())
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-gray-700 mb-3">Categories</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($recipe->categories as $category)
                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Ingredients --}}
            <div class="mb-8">
                <h2 class="text-lg font-bold text-gray-700 mb-3">
                    Ingredients <span class="text-sm font-normal text-gray-400">({{ $recipe->ingredients->count() }} items)</span>
                </h2>
                <ul class="space-y-2">
                    @foreach($recipe->ingredients as $ingredient)
                        <li class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <span class="w-2 h-2 bg-amber-400 rounded-full"></span>
                            <span class="font-medium text-gray-700">{{ $ingredient->amount }}</span>
                            <span class="text-gray-500">{{ $ingredient->name }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Steps --}}
            <div>
                <h2 class="text-lg font-bold text-gray-700 mb-3">
                    Steps <span class="text-sm font-normal text-gray-400">({{ $recipe->steps->count() }} steps)</span>
                </h2>
                <div class="space-y-4">
                    @foreach($recipe->steps as $step)
                        <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                            <div class="w-8 h-8 bg-amber-500 text-white rounded-full flex items-center justify-center font-bold shrink-0">
                                {{ $step->order }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-700 mb-1">{{ $step->title }}</p>
                                <p class="text-gray-500">{{ $step->instruction }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection
