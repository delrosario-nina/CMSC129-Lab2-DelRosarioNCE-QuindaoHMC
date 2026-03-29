{{--
    recipe-card.blade.php
    Used on index.blade.php as a diary table-of-contents row.
    Expects: $recipe (with ->categories relationship)
             $page  (the "page number" shown on the right, defaults to loop index)
--}}

@props(['recipe', 'page' => 1])

<a href="{{ route('recipes.show', $recipe) }}" class="recipe-row block group">
    <div class="flex items-baseline gap-0">
        {{-- Title --}}
        <span class="diary-title text-xl shrink-0 group-hover:text-amber-700">
            {{ $recipe->title }}
        </span>

        {{-- Dotted leader --}}
        <span class="leader flex-1 mx-3 mb-1 border-b-2 border-dotted border-gray-400 self-end"></span>

        {{-- Page number --}}
        <span class="diary-body text-base shrink-0 text-gray-700">p.{{ $page }}</span>
    </div>

    {{-- Tags --}}
    @if($recipe->categories->isNotEmpty())
        <div class="flex flex-wrap gap-1.5 mt-1.5 mb-1">
            @foreach($recipe->categories as $category)
                @include('components.recipe-tag', ['name' => $category->name])
            @endforeach
        </div>
    @endif
</a>
