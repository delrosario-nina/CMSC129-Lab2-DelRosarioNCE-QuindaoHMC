@extends('layouts.app')
@section('title', 'Add New Recipe')
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600&family=DM+Sans:wght@300;400;500&display=swap');

.rfp {
    font-family: 'DM Sans', sans-serif;
    min-height: 100vh;
    background: url('/assets/bg.png') center/cover no-repeat;
    padding: 2rem 1rem;
}
.back-link {
    display: inline-flex; align-items: center; gap: 0.4rem;
    color: #92400e; font-size: 0.875rem; font-weight: 500;
    text-decoration: none; opacity: 0.7; transition: opacity 0.2s; margin-bottom: 1.75rem;
}
.back-link:hover { opacity: 1; }

/* Card */
.form-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15), 0 10px 25px rgba(0, 0, 0, 0.1);
    max-width: 1000px;
    margin: 0 auto;
    overflow: hidden;
    padding: 3rem;
}

/* Stepper header */
.stepper-header {
    padding: 1.75rem 2rem 0;
}
.stepper-track {
    display: flex; align-items: flex-start; gap: 0; margin-bottom: 2rem; position: relative;
}
.stepper-track::before {
    content: ''; position: absolute; top: 1.125rem; left: 1.125rem;
    right: 1.125rem; height: 2px; background: #f0e8de; z-index: 0;
}
.step-node {
    display: flex; flex-direction: column; align-items: center; gap: 0.4rem;
    flex: 1; position: relative; z-index: 1;
}
.step-bubble {
    width: 2.25rem; height: 2.25rem; border-radius: 50%; border: 2px solid #e8ddd4;
    background: #ffffff; display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem; font-weight: 600; color: #c4a882;
    transition: all 0.3s ease; font-family: 'Lora', serif;
}
.step-bubble.active {
    background: linear-gradient(135deg, #fbbf24, #f59e0b);
    border-color: #f59e0b; color: #ffffff;
    box-shadow: 0 0 0 4px rgba(245,158,11,0.15);
}
.step-bubble.done {
    background: #fef3c7; border-color: #fde68a; color: #92400e;
}
.step-label {
    font-size: 0.6875rem; font-weight: 500; color: #c4a882; white-space: nowrap;
    transition: color 0.3s;
}
.step-label.active { color: #92400e; }
.step-label.done   { color: #b45309; }

/* Progress bar */
.progress-bar-track {
    height: 3px; background: #f0e8de; border-radius: 99px; margin-bottom: 0; overflow: hidden;
}
.progress-bar-fill {
    height: 100%; background: linear-gradient(90deg, #fbbf24, #f59e0b);
    border-radius: 99px; transition: width 0.4s cubic-bezier(0.4,0,0.2,1);
}

/* Step panels */
.step-panel { padding: 2rem; display: none; }
.step-panel.active { display: block; }
.step-title {
    font-family: 'Lora', serif; font-size: 1.375rem; font-weight: 600;
    color: #1c1410; margin-bottom: 0.25rem;
}
.step-desc { font-size: 0.875rem; color: #a07850; margin-bottom: 1.75rem; }

/* Fields */
.field-group { margin-bottom: 1.25rem; }
.field-label { display: block; font-size: 0.8125rem; font-weight: 500; color: #6b5240; margin-bottom: 0.375rem; }
.field-label .req { color: #f87171; }
.fi, .ft, .fs {
    width: 100%; background: #fdfaf7; border: 1.5px solid #e8ddd4; border-radius: 0.75rem;
    padding: 0.625rem 0.875rem; font-size: 0.9375rem; font-family: 'DM Sans', sans-serif;
    color: #1c1410; transition: border-color 0.2s, box-shadow 0.2s, background 0.2s; outline: none;
    box-sizing: border-box;
}
.fi:focus, .ft:focus, .fs:focus { border-color: #f59e0b; background: #fff; box-shadow: 0 0 0 3px rgba(245,158,11,0.1); }
.fi.err, .ft.err { border-color: #fca5a5; background: #fff5f5; }
.ft { resize: vertical; min-height: 80px; }
.fs {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23a07850' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 0.875rem center;
    padding-right: 2.5rem; cursor: pointer;
}
.err-msg { color: #ef4444; font-size: 0.75rem; margin-top: 0.3rem; }
.grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
@media (max-width: 560px) { .grid-3 { grid-template-columns: 1fr; } }

/* Image upload */
.upload-zone {
    border: 2px dashed #e8ddd4; border-radius: 0.875rem; padding: 2rem;
    text-align: center; background: #fdfaf7; cursor: pointer; position: relative;
    transition: border-color 0.2s, background 0.2s;
}
.upload-zone:hover { border-color: #f59e0b; background: #fffbf0; }
.upload-zone input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.upload-icon { font-size: 2rem; display: block; margin-bottom: 0.5rem; }
.upload-text { font-size: 0.875rem; color: #a07850; font-weight: 500; }
.upload-hint { font-size: 0.75rem; color: #c4a882; margin-top: 0.25rem; }
.upload-filename { font-size: 0.8rem; color: #92400e; margin-top: 0.5rem; font-weight: 500; }

/* Category selects */
.cat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem; }
@media (max-width: 480px) { .cat-grid { grid-template-columns: 1fr; } }
.cat-type-label { display: block; font-size: 0.75rem; font-weight: 500; margin-bottom: 0.3rem; }
.tl-complexity { color: #be123c; } .tl-time { color: #1d4ed8; } .tl-type { color: #065f46; }
.tl-protein { color: #4338ca; }   .tl-status { color: #b45309; }
.cs-complexity { border-color: #fecdd3 !important; background: #fff5f7 !important; }
.cs-time       { border-color: #bfdbfe !important; background: #f5f8ff !important; }
.cs-type       { border-color: #a7f3d0 !important; background: #f0fdf8 !important; }
.cs-protein    { border-color: #c7d2fe !important; background: #f5f5ff !important; }
.cs-status     { border-color: #fde68a !important; background: #fffbeb !important; }
.cs-complexity:focus { border-color: #fb7185 !important; box-shadow: 0 0 0 3px rgba(251,113,133,0.12) !important; }
.cs-time:focus       { border-color: #60a5fa !important; box-shadow: 0 0 0 3px rgba(96,165,250,0.12) !important; }
.cs-type:focus       { border-color: #34d399 !important; box-shadow: 0 0 0 3px rgba(52,211,153,0.12) !important; }
.cs-protein:focus    { border-color: #818cf8 !important; box-shadow: 0 0 0 3px rgba(129,140,248,0.12) !important; }
.cs-status:focus     { border-color: #fbbf24 !important; box-shadow: 0 0 0 3px rgba(251,191,36,0.12) !important; }

/* Tags */
.tags-box {
    display: flex; flex-wrap: wrap; gap: 0.5rem; min-height: 2.5rem;
    padding: 0.625rem; background: #fdfaf7; border-radius: 0.75rem;
    border: 1.5px solid #f0e8de; margin-top: 0.5rem;
}
.cat-tag {
    display: inline-flex; align-items: center; gap: 0.375rem;
    padding: 0.3125rem 0.625rem 0.3125rem 0.75rem; border-radius: 999px;
    font-size: 0.75rem; font-weight: 500;
    animation: tagPop 0.2s cubic-bezier(0.34,1.56,0.64,1);
}
@keyframes tagPop { from { transform: scale(0.7); opacity: 0; } to { transform: scale(1); opacity: 1; } }
.tag-complexity { background: #fff0f3; color: #be123c; border: 1px solid #fecdd3; }
.tag-time       { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.tag-type       { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.tag-protein    { background: #eef2ff; color: #4338ca; border: 1px solid #c7d2fe; }
.tag-status     { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.tag-x {
    width: 1.125rem; height: 1.125rem; border-radius: 50%; border: none;
    background: rgba(0,0,0,0.08); cursor: pointer; display: inline-flex;
    align-items: center; justify-content: center; font-size: 0.6rem;
    transition: background 0.15s; padding: 0; line-height: 1;
}
.tag-x:hover { background: rgba(0,0,0,0.18); }
.tags-hint { font-size: 0.7rem; color: #c4a882; margin-top: 0.375rem; }

/* Dynamic rows */
.row-anim { animation: slideIn 0.2s ease; }
@keyframes slideIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
.ing-row { display: flex; gap: 0.625rem; align-items: center; }
.ing-row .amt { width: 36%; flex-shrink: 0; }
.ing-row .nm  { flex: 1; }
.btn-x {
    background: none; border: none; color: #fca5a5; cursor: pointer; font-size: 1rem;
    padding: 0.25rem; border-radius: 0.375rem; transition: color 0.15s, background 0.15s; line-height: 1; flex-shrink: 0;
}
.btn-x:hover { color: #ef4444; background: #fff1f1; }
.btn-add {
    display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; font-weight: 500;
    color: #92400e; background: #fef3c7; border: 1.5px solid #fde68a; border-radius: 0.625rem;
    padding: 0.4rem 0.875rem; cursor: pointer; transition: background 0.15s, border-color 0.15s;
}
.btn-add:hover { background: #fde68a; border-color: #fbbf24; }
.step-row { display: flex; gap: 0.875rem; align-items: flex-start; }
.step-num {
    width: 2rem; height: 2rem; background: linear-gradient(135deg, #fbbf24, #f59e0b);
    color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 0.8125rem; font-weight: 700; flex-shrink: 0; margin-top: 0.5rem;
    box-shadow: 0 2px 6px rgba(245,158,11,0.3); font-family: 'Lora', serif;
}
.step-fields { flex: 1; display: flex; flex-direction: column; gap: 0.5rem; }
.space-y  { display: flex; flex-direction: column; gap: 0.75rem; }
.space-lg { display: flex; flex-direction: column; gap: 1rem; }

/* Footer nav */
.step-footer {
    padding: 1.25rem 2rem 2rem;
    display: flex; justify-content: space-between; align-items: center;
    border-top: 1px solid #f3f0eb;
}
.btn-prev {
    background: white; color: #6b7280; border: 1.5px solid #e5e7eb;
    padding: 0.625rem 1.5rem; border-radius: 0.875rem; font-size: 0.9375rem;
    font-family: 'DM Sans', sans-serif; cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
}
.btn-prev:hover { background: #f9fafb; border-color: #d1d5db; }
.btn-next, .btn-save {
    background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #1c1410; border: none;
    padding: 0.625rem 1.75rem; border-radius: 0.875rem; font-size: 0.9375rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif; cursor: pointer;
    transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
    box-shadow: 0 3px 10px rgba(245,158,11,0.35);
}
.btn-next:hover, .btn-save:hover { transform: translateY(-1px); box-shadow: 0 5px 16px rgba(245,158,11,0.45); filter: brightness(1.04); }
.btn-next:active, .btn-save:active { transform: translateY(0); }
.step-counter { font-size: 0.8125rem; color: #c4a882; }
</style>

@php
    $oldCategoryIds    = old('categories', []);
    $selectedByDefault = $categories->whereIn('id', $oldCategoryIds)->values();
@endphp

<div class="rfp">
    <div class="form-card" x-data="{
        currentStep: 1,
        totalSteps: 4,
        steps: ['Basic Info', 'Categories', 'Ingredients', 'Steps'],
        ingredients: [{ name: '', amount: '' }],
        cookingSteps: [{ title: '', instruction: '' }],
        allCategories:      {{ Js::from($categories) }},
        selectedCategories: {{ Js::from($selectedByDefault) }},
        selectedType:       {{ Js::from(array_fill_keys(array_keys($categoryTypes), '')) }},
        typeColors:         {{ Js::from($typeColors) }},
        filename: '',

        get progressWidth() {
            return ((this.currentStep - 1) / (this.totalSteps - 1) * 100) + '%';
        },
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
        },
        nextStep() { if (this.currentStep < this.totalSteps) this.currentStep++; },
        prevStep() { if (this.currentStep > 1) this.currentStep--; },
    }">

        <a href="{{ route('recipes.index') }}" class="back-link">Back to Recipes</a>

        {{-- ── Stepper Header ── --}}
        <div class="stepper-header">
            <div class="stepper-track">
                <template x-for="(label, i) in steps" :key="i">
                    <div class="step-node">
                        <div class="step-bubble"
                            :class="{ 'active': currentStep === i+1, 'done': currentStep > i+1 }">
                            <template x-if="currentStep > i+1">Done</template>
                            <template x-if="currentStep <= i+1">
                                <span x-text="i+1"></span>
                            </template>
                        </div>
                        <span class="step-label"
                            :class="{ 'active': currentStep === i+1, 'done': currentStep > i+1 }"
                            x-text="label"></span>
                    </div>
                </template>
            </div>
            <div class="progress-bar-track">
                <div class="progress-bar-fill" :style="'width:' + progressWidth"></div>
            </div>
        </div>

        <form action="{{ route('recipes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ══ Step 1: Basic Info ══ --}}
            <div class="step-panel" :class="{ active: currentStep === 1 }">
                <h2 class="step-title">Basic Info</h2>
                <p class="step-desc">Tell us what you're making</p>

                <div class="field-group">
                    <label class="field-label">Recipe Title <span class="req">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        placeholder="e.g. Grandma's Chocolate Cake"
                        class="fi {{ $errors->has('title') ? 'err' : '' }}">
                    @error('title')<p class="err-msg">{{ $message }}</p>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label">Description <span class="req">*</span></label>
                    <textarea name="description" rows="3"
                        placeholder="A brief, appetizing description..."
                        class="ft {{ $errors->has('description') ? 'err' : '' }}">{{ old('description') }}</textarea>
                    @error('description')<p class="err-msg">{{ $message }}</p>@enderror
                </div>

                <div class="grid-3">
                    <div class="field-group">
                        <label class="field-label">Prep Time (min) <span class="req">*</span></label>
                        <input type="number" name="prep_time" value="{{ old('prep_time') }}" min="0"
                            placeholder="15" class="fi {{ $errors->has('prep_time') ? 'err' : '' }}">
                        @error('prep_time')<p class="err-msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label">Cook Time (min) <span class="req">*</span></label>
                        <input type="number" name="cook_time" value="{{ old('cook_time') }}" min="0"
                            placeholder="30" class="fi {{ $errors->has('cook_time') ? 'err' : '' }}">
                        @error('cook_time')<p class="err-msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label">Difficulty <span class="req">*</span></label>
                        <select name="difficulty" class="fs">
                            @foreach(['easy' => '🟢 Easy', 'medium' => '🟡 Medium', 'hard' => '🔴 Hard'] as $value => $label)
                                <option value="{{ $value }}" {{ old('difficulty','easy') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Recipe Photo</label>
                    <div class="upload-zone">
                        <input type="file" name="image" accept="image/*"
                            @change="filename = $event.target.files[0]?.name ?? ''">
                        <span class="upload-icon">📷</span>
                        <p class="upload-text">Click to upload a photo</p>
                        <p class="upload-hint">JPEG, PNG, WebP — max 2 MB</p>
                        <p class="upload-filename" x-show="filename" x-text="filename"></p>
                    </div>
                    @error('image')<p class="err-msg">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- ══ Step 2: Categories ══ --}}
            <div class="step-panel" :class="{ active: currentStep === 2 }">
                <h2 class="step-title">Categories</h2>
                <p class="step-desc">Help people find your recipe</p>

                <div class="cat-grid">
                    @foreach($categoryTypes as $typeKey => $typeMeta)
                        <div>
                            <label class="cat-type-label tl-{{ $typeKey }}">{{ $typeMeta['label'] }}</label>
                            <select
                                x-model="selectedType['{{ $typeKey }}']"
                                @change="addCategory('{{ $typeKey }}')"
                                class="fs cs-{{ $typeKey }}"
                            >
                                <option value="">Pick {{ $typeMeta['label'] }}…</option>
                                @foreach($groupedCategories[$typeKey] ?? [] as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="tags-box">
                    <template x-for="category in selectedCategories" :key="category.id">
                        <span class="cat-tag" :class="'tag-' + category.type">
                            <span x-text="category.name"></span>
                            <button type="button" class="tag-x" @click="removeCategory(category.id)">Remove</button>
                            <input type="hidden" name="categories[]" :value="category.id">
                        </span>
                    </template>
                    <span x-show="selectedCategories.length === 0"
                        style="font-size:0.75rem;color:#c4a882;padding:0.125rem 0.25rem;">
                        No categories selected yet
                    </span>
                </div>
                <p class="tags-hint">Select options above — they appear as removable tags. Categories are optional.</p>

                @error('categories')<p class="err-msg">{{ $message }}</p>@enderror
            </div>

            {{-- ══ Step 3: Ingredients ══ --}}
            <div class="step-panel" :class="{ active: currentStep === 3 }">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.25rem;">
                    <div>
                        <h2 class="step-title" style="margin-bottom:0;">Ingredients</h2>
                        <p class="step-desc">What goes into this recipe</p>
                    </div>
                    <button type="button" class="btn-add" style="margin-top:0.25rem;"
                        @click="ingredients.push({ name: '', amount: '' })">+ Add</button>
                </div>

                @error('ingredients')<p class="err-msg" style="margin-bottom:0.75rem;">{{ $message }}</p>@enderror

                <div class="space-y">
                    <template x-for="(ingredient, index) in ingredients" :key="index">
                        <div class="ing-row row-anim">
                            <input type="text" :name="`ingredients[${index}][amount]`"
                                placeholder="Amount (e.g. 2 cups)"
                                x-model="ingredient.amount" class="fi amt">
                            <input type="text" :name="`ingredients[${index}][name]`"
                                placeholder="Ingredient name"
                                x-model="ingredient.name" class="fi nm">
                            <button type="button" class="btn-x"
                                @click="ingredients.splice(index, 1)"
                                x-show="ingredients.length > 1">✕</button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- ══ Step 4: Steps ══ --}}
            <div class="step-panel" :class="{ active: currentStep === 4 }">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.25rem;">
                    <div>
                        <h2 class="step-title" style="margin-bottom:0;">Steps</h2>
                        <p class="step-desc">Walk cooks through the process</p>
                    </div>
                    <button type="button" class="btn-add" style="margin-top:0.25rem;"
                        @click="cookingSteps.push({ title: '', instruction: '' })">+ Add</button>
                </div>

                @error('steps')<p class="err-msg" style="margin-bottom:0.75rem;">{{ $message }}</p>@enderror

                <div class="space-lg">
                    <template x-for="(step, index) in cookingSteps" :key="index">
                        <div class="step-row row-anim">
                            <div class="step-num" x-text="index + 1"></div>
                            <div class="step-fields">
                                <input type="text" :name="`steps[${index}][title]`"
                                    placeholder="Step title (e.g. Prepare the batter)"
                                    x-model="step.title" class="fi">
                                <textarea :name="`steps[${index}][instruction]`"
                                    placeholder="Describe what to do..."
                                    x-model="step.instruction" rows="2" class="ft"></textarea>
                            </div>
                            <button type="button" class="btn-x"
                                @click="cookingSteps.splice(index, 1)"
                                x-show="cookingSteps.length > 1" style="margin-top:0.5rem;">✕</button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- ── Footer Nav ── --}}
            <div class="step-footer">
                <button type="button" class="btn-prev"
                    @click="prevStep()" x-show="currentStep > 1">Back</button>
                <div x-show="currentStep === 1"></div>{{-- spacer --}}

                <span class="step-counter" x-text="`Step ${currentStep} of ${totalSteps}`"></span>

                <button type="button" class="btn-next"
                    @click="nextStep()"
                    x-show="currentStep < totalSteps">Continue →</button>

                <button type="submit" class="btn-save"
                    x-show="currentStep === totalSteps">Save Recipe</button>
            </div>

        </form>
    </div>
</div>

@endsection
