@props(['recipe'])

@php
    $categoryColors = [
        // Complexity
        'no-sweat' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
        'busy-hands' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
        'culinary-class-wars' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],

        // Time
        'fast-and-furious' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
        'one-kdrama-episode' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800'],
        'one-piece-arc' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800'],

        // Type (Meal/Dish)
        'vegetables' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
        'dessert' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
        'soup' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
        'bread' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
        'breakfast' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
        'dinner' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
        'lunch' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],

        // Protein
        'chicken' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
        'pork' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
        'beef' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
        'fish' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
        'egg' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
        'beans' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
        'none' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],

        // Status
        'first-time' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
        'classic' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
        'mastered' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
    ];

    $getCategoryStyle = function($slug) use ($categoryColors) {
        return $categoryColors[$slug] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
    };
@endphp

<div class="group max-w-xs overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
    <a href="{{ route('recipes.show', $recipe) }}" class="block">
        <div class="h-40 w-full overflow-hidden bg-gray-100">
            @if($recipe->image_path)
                <img src="{{ Storage::url($recipe->image_path) }}" alt="{{ $recipe->title }}" class="h-full w-full object-cover transition duration-300 ease-in-out group-hover:scale-105" />
            @else
                <div class="flex h-full w-full items-center justify-center text-gray-400">No Image</div>
            @endif
        </div>

        <div class="p-3">
            @if($recipe->categories->isNotEmpty())
                <div class="mb-3 flex flex-wrap gap-2">
                    @php
                        $maxTags = 3;
                        $totalTags = $recipe->categories->count();
                        $visibleTags = $recipe->categories->take($maxTags);
                        $hiddenCount = max(0, $totalTags - $maxTags);
                    @endphp

                    @foreach($visibleTags as $category)
                        @php $style = $getCategoryStyle($category->slug); @endphp
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $style['bg'] }} {{ $style['text'] }}">
                            {{ $category->name }}
                        </span>
                    @endforeach

                    @if($hiddenCount > 0)
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">+{{ $hiddenCount }}</span>
                    @endif
                </div>
            @endif

            <h2 class="text-xl font-bold text-gray-900 leading-tight transition-colors duration-150 group-hover:text-amber-700">{{ $recipe->title }}</h2>

            @if(!empty($recipe->description))
                <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ \Illuminate\Support\Str::limit($recipe->description, 80) }}</p>
            @endif
        </div>
    </a>
</div>
