@props(['recipe', 'page' => 1])

<div x-data="{ showConfirm: false }" class="recipe-row group relative">

    {{-- Confirm modal --}}
    <template x-if="showConfirm">
        <div
            style="position:fixed; inset:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; z-index:9999;"
            @click.self="showConfirm = false"
            @keydown.escape.window="showConfirm = false"
        >
            <div style="background:#fff; border-radius:1rem; border:1px solid #e5e5e5; box-shadow:0 8px 32px rgba(0,0,0,0.18); max-width:420px; width:90%; padding:2rem; text-align:center;" @click.stop>
                <h3 style="margin:0 0 0.75rem; color:#111; font-size:1.1rem; font-weight:700;">Move to Trash?</h3>
                <p style="margin:0 0 1.5rem; color:#444; font-size:0.95rem; line-height:1.5;">
                    <strong>{{ $recipe->title }}</strong> will be moved to trash. You can restore it later.
                </p>
                <div style="display:flex; justify-content:center; gap:0.75rem;">
                    <form action="{{ route('recipes.destroy', $recipe->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            style="background:#000; color:#fff; border:none; border-radius:0.5rem; padding:0.65rem 1.35rem; cursor:pointer; font-size:0.9rem; font-weight:600;"
                        >Yes, delete</button>
                    </form>
                    <button
                        type="button"
                        @click="showConfirm = false"
                        style="background:#f5f5f5; color:#333; border:1px solid #dcdcdc; border-radius:0.5rem; padding:0.65rem 1.35rem; cursor:pointer; font-size:0.9rem; font-weight:600;"
                    >Cancel</button>
                </div>
            </div>
        </div>
    </template>

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
