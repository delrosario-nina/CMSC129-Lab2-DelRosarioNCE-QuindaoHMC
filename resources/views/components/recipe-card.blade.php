@props(['recipe', 'page' => 1])

<div x-data="{ showConfirm: false }"
     @flashConfirm="$refs.deleteRecipeForm && $refs.deleteRecipeForm.submit(); showConfirm = false"
     @flashCancel="showConfirm = false"
     class="recipe-row group relative">

    {{-- Confirm modal --}}
    <template x-if="showConfirm">
        <x-flash-message
            type="warning"
            modal
            confirm
            title="Move to Trash?"
            message="Move '{{ $recipe->title }}' to trash? You can restore it later."
            confirmText="Yes, move"
            cancelText="Cancel"
        />
    </template>

    <form x-ref="deleteRecipeForm" action="{{ route('recipes.destroy', $recipe->id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    {{-- Row — div instead of <a>, navigates on click unless delete button --}}
    <div
        class="block cursor-pointer"
        @click="window.location='{{ route('recipes.show', $recipe) }}'"
    >
        <div class="flex items-baseline gap-0">
            <span class="diary-title text-xl shrink-0 group-hover:text-amber-700">
                {{ $recipe->title }}
            </span>
            <span class="leader flex-1 mx-3 mb-1 border-b-2 border-dotted border-gray-400 self-end"></span>
            <span class="diary-body text-base shrink-0 text-gray-700">p.{{ $page }}</span>
        </div>

        @if($recipe->categories->isNotEmpty())
            <div class="flex flex-wrap gap-1.5 mt-1.5 mb-1">
                @foreach($recipe->categories as $category)
                    @include('components.recipe-tag', ['name' => $category->name])
                @endforeach
            </div>
        @endif
    </div>

    {{-- Delete button --}}
    <button
        type="button"
        @click.stop="showConfirm = true"
        class="absolute top-1/2 -translate-y-1/2 right-0 opacity-0 group-hover:opacity-100 transition-opacity duration-150 text-gray-400 hover:text-red-500 text-xs font-medium diary-body px-2 py-1 rounded hover:bg-red-50 flex items-center gap-1"
        title="Move to trash"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"/>
            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
            <path d="M10 11v6M14 11v6"/>
            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
        </svg>
        Delete
    </button>

</div>
