<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition overflow-hidden border border-amber-100">

    {{-- Image --}}
    @if($recipe->image_path)
        <img src="{{ Storage::url($recipe->image_path) }}"
             alt="{{ $recipe->title }}"
             class="w-full h-48 object-cover">
    @else
        <div class="w-full h-48 bg-amber-100 flex items-center justify-center">
            <span class="text-5xl">🍽️</span>
        </div>
    @endif

    <div class="p-4">
        {{-- Title --}}
        <h3 class="font-bold text-lg text-gray-800 mb-1">{{ $recipe->title }}</h3>

        {{-- Description --}}
        <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $recipe->description }}</p>

        {{-- Meta --}}
        <div class="flex gap-3 text-xs text-gray-400 mb-3">
            <span>⏱ Prep: {{ $recipe->prep_time }}min</span>
            <span>🔥 Cook: {{ $recipe->cook_time }}min</span>
            <span class="capitalize">📊 {{ $recipe->difficulty }}</span>
        </div>

        {{-- Categories --}}
        @if($recipe->categories->isNotEmpty())
            <div class="flex flex-wrap gap-1 mb-3">
                @foreach($recipe->categories as $category)
                    <span class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded-full">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Actions --}}
        <div class="flex gap-2 mt-2">
            <a href="{{ route('recipes.show', $recipe) }}"
               class="flex-1 text-center bg-amber-500 text-white text-sm py-2 rounded-lg hover:bg-amber-600 transition">
                View
            </a>
            <a href="{{ route('recipes.edit', $recipe) }}"
               class="flex-1 text-center border border-amber-500 text-amber-500 text-sm py-2 rounded-lg hover:bg-amber-50 transition">
                Edit
            </a>
            <form action="{{ route('recipes.destroy', $recipe) }}" method="POST"
                  onsubmit="return confirm('Move to trash?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="border border-red-400 text-red-400 text-sm px-3 py-2 rounded-lg hover:bg-red-50 transition">
                    🗑
                </button>
            </form>
        </div>
    </div>
</div>
