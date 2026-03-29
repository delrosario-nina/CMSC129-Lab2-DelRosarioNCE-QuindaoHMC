@props(['recipe'])

@php
    $tagPalette = [
        ['bg' => 'bg-pink-100', 'text' => 'text-pink-800'],
        ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800'],
        ['bg' => 'bg-sky-100', 'text' => 'text-sky-800'],
        ['bg' => 'bg-violet-100', 'text' => 'text-violet-800'],
        ['bg' => 'bg-amber-100', 'text' => 'text-amber-800'],
        ['bg' => 'bg-rose-100', 'text' => 'text-rose-800'],
    ];

    $tagStyle = function($index) use ($tagPalette) {
        return $tagPalette[$index % count($tagPalette)];
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

                    @foreach($visibleTags as $idx => $category)
                        @php $style = $tagStyle($idx); @endphp
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
