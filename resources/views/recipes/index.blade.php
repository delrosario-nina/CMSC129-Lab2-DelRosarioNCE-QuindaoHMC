@extends('layouts.app')

@section('title', 'My Recipe Diary')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">My Recipe Book</h1>
        <a href="{{ route('recipes.create') }}"
           class="bg-amber-500 text-white px-6 py-2 rounded-lg hover:bg-amber-600 transition">
            + Add Recipe
        </a>
    </div>

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('recipes.index') }}" class="mb-8 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search recipes..."
               class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">

        <select name="difficulty"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
            <option value="">All Difficulties</option>
            <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
            <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
        </select>

        <select name="category"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
            <option value="">All Categories</option>
            @foreach(\App\Models\Category::all() as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <button type="submit"
                class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition">
            Search
        </button>

        @if(request()->anyFilled(['search', 'difficulty', 'category']))
            <a href="{{ route('recipes.index') }}"
               class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                Clear
            </a>
        @endif
    </form>

    {{-- Recipe Grid --}}
    @if($recipes->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <p class="text-6xl mb-4">📭</p>
            <p class="text-xl">You have no recipes yet.</p>
            <a href="{{ route('recipes.create') }}"
               class="mt-4 inline-block bg-amber-500 text-white px-6 py-2 rounded-lg hover:bg-amber-600 transition">
                Add your first recipe!
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recipes as $recipe)
                @include('components.recipe-card', compact('recipe'))
            @endforeach
        </div>

        <div class="mt-8">
            {{ $recipes->withQueryString()->links() }}
        </div>
    @endif
@endsection
