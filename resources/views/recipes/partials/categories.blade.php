@php
    $oldCategoryIds    = old('categories', []);
    $selectedByDefault = $categories->whereIn('id', $oldCategoryIds)->values();
@endphp

<div class="section-heading">
    <span class="icon">🏷</span> Categories
</div>

<div x-data="{
    allCategories:      {{ Js::from($categories) }},
    selectedCategories: {{ Js::from($selectedByDefault) }},
    selectedType:       {{ Js::from(array_fill_keys(array_keys($categoryTypes), '')) }},
    typeColors:         {{ Js::from($typeColors) }},
    addCategory(type) {
        const id = this.selectedType[type];
        if (!id) return;
        if (!this.selectedCategories.find(c => c.id === Number(id))) {
            const cat = this.allCategories.find(c => c.id === Number(id));
            if (cat) this.selectedCategories.push(cat);
        }
        this.selectedType[type] = '';
    },
    removeCategory(id) {
        this.selectedCategories = this.selectedCategories.filter(c => c.id !== id);
    }
}">
    <div class="category-grid">
        @foreach($categoryTypes as $typeKey => $typeMeta)
            <div class="category-select-wrapper">
                <label class="type-label-{{ $typeKey }}">{{ $typeMeta['label'] }}</label>
                <select
                    x-model="selectedType['{{ $typeKey }}']"
                    @change="addCategory('{{ $typeKey }}')"
                    class="form-select category-select-{{ $typeKey }}"
                >
                    <option value="">Pick {{ $typeMeta['label'] }}…</option>
                    @foreach($groupedCategories[$typeKey] ?? [] as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </div>

    <div class="tags-container">
        <template x-for="category in selectedCategories" :key="category.id">
            <span class="category-tag" :class="'tag-' + category.type">
                <span x-text="category.name"></span>
                <button type="button" class="tag-remove" @click="removeCategory(category.id)">✕</button>
                <input type="hidden" name="categories[]" :value="category.id">
            </span>
        </template>
        <span x-show="selectedCategories.length === 0"
            style="font-size:0.75rem;color:#c4a882;padding:0.125rem 0.25rem;">
            No categories selected yet
        </span>
    </div>
    <p class="tags-hint">Select options above — they appear as removable tags.</p>

    @error('categories')<p class="error-msg">{{ $message }}</p>@enderror
</div>
