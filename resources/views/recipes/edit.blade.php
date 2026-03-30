@extends('layouts.app')
@section('title', 'Edit ' . $recipe->title)
@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600&family=DM+Sans:wght@300;400;500&display=swap');

.rfp { font-family: 'DM Sans', sans-serif; min-height: 100vh; background: #ffffff; padding: 2rem 1rem; }
.form-card { background: #ffffff; border-radius: 1rem; border: 1px solid #e5e5e5; box-shadow: 0 2px 8px rgba(0,0,0,0.08); max-width: 1000px; margin: 0 auto; overflow: hidden; padding: 3rem; }
.stepper-header { padding: 1.75rem 2rem 0; }
.stepper-track { display: flex; align-items: flex-start; gap: 0; margin-bottom: 2rem; position: relative; }
.stepper-track::before { content: ''; position: absolute; top: 1.125rem; left: 1.125rem; right: 1.125rem; height: 2px; background: #e5e5e5; z-index: 0; }
.step-node { display: flex; flex-direction: column; align-items: center; gap: 0.4rem; flex: 1; position: relative; z-index: 1; }
.step-bubble { width: 2.25rem; height: 2.25rem; border-radius: 50%; border: 2px solid #d0d0d0; background: #f5f5f5; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 600; color: #999999; font-family: 'Lora', serif; }
.step-bubble.active { background: #000000; border-color: #000000; color: #ffffff; }
.step-bubble.done { background: #f5f5f5; border-color: #d0d0d0; color: #666666; }
.step-label { font-size: 0.6875rem; font-weight: 500; color: #999999; white-space: nowrap; }
.step-label.active { color: #000000; font-weight: 600; }
.step-label.done { color: #666666; }
.progress-bar-track { height: 2px; background: #e5e5e5; border-radius: 99px; margin-bottom: 0; overflow: hidden; }
.progress-bar-fill { height: 100%; background: #000000; border-radius: 99px; }
.step-panel { padding: 2rem; display: none; }
.step-panel.active { display: block; }
.step-title { font-family: 'DM Sans', sans-serif; font-size: 1.375rem; font-weight: 600; color: #000000; margin-bottom: 0.25rem; }
.step-desc { font-size: 0.875rem; color: #666666; margin-bottom: 1.75rem; }
.field-group { margin-bottom: 1.25rem; }
.field-label { display: block; font-size: 0.8125rem; font-weight: 500; color: #333333; margin-bottom: 0.375rem; }
.field-label .req { color: #d97757; }
.fi, .ft, .fs { width: 100%; background: #ffffff; border: 1.5px solid #d0d0d0; border-radius: 0.5rem; padding: 0.625rem 0.875rem; font-size: 0.9375rem; font-family: 'DM Sans', sans-serif; color: #000000; outline: none; box-sizing: border-box; }
.fi:focus, .ft:focus, .fs:focus { border-color: #000000; background: #fff; box-shadow: 0 0 0 3px rgba(0,0,0,0.08); }
.fi.err, .ft.err { border-color: #d97757; background: #fff8f6; }
.ft { resize: vertical; min-height: 80px; }
.fs { appearance: none; background-color: #fdfaf7; border: 1.5px solid #e8ddd4; border-radius: 1rem; padding: 0.75rem 2.75rem 0.75rem 0.875rem; color: #1c1410; cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%237f6758' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 0.875rem center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
.fs:focus { border-color: #b89a7f; box-shadow: 0 0 0 3px rgba(184,154,127,0.18); }
.err-msg { color: #ef4444; font-size: 0.75rem; margin-top: 0.3rem; }
.grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
@media (max-width: 560px) { .grid-3 { grid-template-columns: 1fr; } }
.upload-zone { border: 2px dashed #d0d0d0; border-radius: 0.5rem; padding: 2rem; text-align: center; background: #fafafa; cursor: pointer; position: relative; }
.upload-zone:hover { border-color: #999999; background: #f5f5f5; }
.upload-zone input { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
.upload-icon { font-size: 2rem; display: block; margin-bottom: 0.5rem; }
.upload-text { font-size: 0.875rem; color: #666666; font-weight: 500; }
.upload-hint { font-size: 0.75rem; color: #999999; margin-top: 0.25rem; }
.upload-filename { font-size: 0.8rem; color: #333333; margin-top: 0.5rem; font-weight: 500; }
.current-image { width: 5rem; height: 5rem; object-fit: cover; border-radius: 0.5rem; border: 1.5px solid #e5e5e5; margin-bottom: 0.75rem; }
.cat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem; }
@media (max-width: 800px) { .cat-grid { grid-template-columns: 1fr; } }
.cat-type-label { display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 0.5rem; color: #333333; }
.tl-complexity { color: #d97757; } .tl-time { color: #8b7355; } .tl-type { color: #8b7355; } .tl-protein { color: #8b7355; } .tl-status { color: #d97757; }
.category-options { display: flex; flex-wrap: wrap; gap: 0.45rem; }
.cat-chip { display: inline-flex; align-items: center; gap: 0.3rem; border: 1px solid #d0d0d0; background: #ffffff; padding: 0.35rem 0.65rem; border-radius: 999px; font-size: 0.85rem; color: #333333; cursor: pointer; transition: all 0.15s ease; }
.cat-chip:hover { border-color: #999999; background-color: #f5f5f5; }
.cat-chip-selected { border-color: #000000; background-color: #000000; color: #ffffff; }
.cat-chip-remove { display: none; font-size: 0.85rem; }
.cat-chip-selected .cat-chip-remove { display: inline-block; color: #ffffff; background: rgba(255,255,255,0.2); padding: 0 0.25rem; border-radius: 999px; font-weight: 700; }
.tags-hint { font-size: 0.8rem; color: #666666; margin-top: 0.5rem; }
.ing-row { display: flex; gap: 0.625rem; align-items: center; }
.ing-row .amt { width: 36%; flex-shrink: 0; }
.ing-row .nm { flex: 1; }
.btn-x { background: none; border: none; color: #d97757; cursor: pointer; font-size: 1rem; padding: 0.25rem; border-radius: 0.375rem; line-height: 1; flex-shrink: 0; }
.btn-x:hover { background: #fef8f5; }
.btn-add { display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; font-weight: 500; color: #8b7355; background: #fdfaf5; border: 1.5px solid #e8dcc0; border-radius: 0.5rem; padding: 0.4rem 0.875rem; cursor: pointer; }
.btn-add:hover { background: #f5ede0; border-color: #dcc8ad; }
.step-row { display: flex; gap: 0.875rem; align-items: flex-start; }
.step-num { width: 2rem; height: 2rem; background: #000000; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8125rem; font-weight: 700; flex-shrink: 0; margin-top: 0.5rem; font-family: 'DM Sans', sans-serif; }
.step-fields { flex: 1; display: flex; flex-direction: column; gap: 0.5rem; }
.space-y { display: flex; flex-direction: column; gap: 0.75rem; }
.space-lg { display: flex; flex-direction: column; gap: 1rem; }
.step-footer { padding: 1.25rem 2rem 2rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #e5e5e5; }
.btn-prev { background: #ffffff; color: #666666; border: 1.5px solid #d0d0d0; padding: 0.625rem 1.5rem; border-radius: 0.5rem; font-size: 0.9375rem; font-family: 'DM Sans', sans-serif; cursor: pointer; }
.btn-prev:hover { background: #f5f5f5; border-color: #999999; }
.btn-next, .btn-save { background: #000000; color: #ffffff; border: none; padding: 0.625rem 1.75rem; border-radius: 0.5rem; font-size: 0.9375rem; font-weight: 600; font-family: 'DM Sans', sans-serif; cursor: pointer; }
.btn-next:hover, .btn-save:hover { background: #1a1a1a; }
.step-counter { font-size: 0.8125rem; color: #999999; }
.back-link { display: inline-block; font-size: 0.875rem; color: #666666; margin-bottom: 1.5rem; font-family: 'DM Sans', sans-serif; }
.back-link:hover { color: #000; }
</style>

@php
    $oldCategoryIds    = old('categories', $recipe->categories->pluck('id')->toArray());
    $selectedByDefault = $categories->whereIn('id', $oldCategoryIds)->values();
@endphp

<div class="rfp">
    <div class="form-card" x-data="{
        currentStep: 1,
        totalSteps: 4,
        steps: ['Basic Info', 'Categories', 'Ingredients', 'Steps'],
        ingredients: {{ $recipe->ingredients->map(fn($i) => ['name' => $i->name, 'amount' => $i->amount])->toJson() }},
        cookingSteps: {{ $recipe->steps->map(fn($s) => ['title' => $s->title, 'instruction' => $s->instruction])->toJson() }},
        allCategories:      {{ Js::from($categories) }},
        selectedCategories: {{ Js::from($selectedByDefault) }},
        filename: '',
        showErrorModal: false,
        errorMessage: '',

        get progressWidth() {
            return ((this.currentStep - 1) / (this.totalSteps - 1) * 100) + '%';
        },
        toggleCategory(category) {
            const index = this.selectedCategories.findIndex(c => c.id === category.id);
            if (index !== -1) { this.selectedCategories.splice(index, 1); }
            else { this.selectedCategories.push(category); }
        },
        validateStep() {
            let errors = [];
            if (this.currentStep === 1) {
                if (!this.$refs.title.value.trim())       errors.push('Title is required');
                if (!this.$refs.description.value.trim()) errors.push('Description is required');
                if (!this.$refs.prep_time.value.trim())   errors.push('Prep time is required');
                if (!this.$refs.cook_time.value.trim())   errors.push('Cook time is required');
            } else if (this.currentStep === 2) {
                if (this.selectedCategories.length === 0) errors.push('At least one category is required');
            } else if (this.currentStep === 3) {
                this.ingredients.forEach((ing, i) => {
                    if (!ing.name.trim())   errors.push(`Ingredient ${i+1} name is required`);
                    if (!ing.amount.trim()) errors.push(`Ingredient ${i+1} amount is required`);
                });
            } else if (this.currentStep === 4) {
                this.cookingSteps.forEach((step, i) => {
                    if (!step.title.trim())       errors.push(`Step ${i+1} title is required`);
                    if (!step.instruction.trim()) errors.push(`Step ${i+1} instruction is required`);
                });
            }
            if (errors.length > 0) {
                this.errorMessage = errors.join('. ');
                this.showErrorModal = true;
                return false;
            }
            return true;
        },
        nextStep() { if (this.currentStep < this.totalSteps && this.validateStep()) this.currentStep++; },
        prevStep() { if (this.currentStep > 1) this.currentStep--; },
    }">

        <a href="{{ route('recipes.show', $recipe) }}" class="back-link">← Back to Recipe</a>

        {{-- Stepper Header --}}
        <div class="stepper-header">
            <div class="stepper-track">
                <template x-for="(label, i) in steps" :key="i">
                    <div class="step-node">
                        <div class="step-bubble" :class="{ 'active': currentStep === i+1, 'done': currentStep > i+1 }">
                            <template x-if="currentStep > i+1">Done</template>
                            <template x-if="currentStep <= i+1"><span x-text="i+1"></span></template>
                        </div>
                        <span class="step-label" :class="{ 'active': currentStep === i+1, 'done': currentStep > i+1 }" x-text="label"></span>
                    </div>
                </template>
            </div>
            <div class="progress-bar-track">
                <div class="progress-bar-fill" :style="'width:' + progressWidth"></div>
            </div>
        </div>

        <form action="{{ route('recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Step 1: Basic Info --}}
            <div class="step-panel" :class="{ active: currentStep === 1 }">
                <h2 class="step-title">Basic Info</h2>
                <p class="step-desc">Update the basics</p>

                <div class="field-group">
                    <label class="field-label">Recipe Title <span class="req">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $recipe->title) }}"
                        placeholder="e.g. Ultimate Pancit Canton"
                        class="fi {{ $errors->has('title') ? 'err' : '' }}" x-ref="title">
                    @error('title')<p class="err-msg">{{ $message }}</p>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label">Description <span class="req">*</span></label>
                    <textarea name="description" rows="3"
                        placeholder="What makes this recipe special?"
                        class="ft {{ $errors->has('description') ? 'err' : '' }}"
                        x-ref="description">{{ old('description', $recipe->description) }}</textarea>
                    @error('description')<p class="err-msg">{{ $message }}</p>@enderror
                </div>

                <div class="grid-3">
                    <div class="field-group">
                        <label class="field-label">Prep Time (min) <span class="req">*</span></label>
                        <input type="number" name="prep_time" value="{{ old('prep_time', $recipe->prep_time) }}" min="0"
                            placeholder="15" class="fi {{ $errors->has('prep_time') ? 'err' : '' }}" x-ref="prep_time">
                        @error('prep_time')<p class="err-msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label">Cook Time (min) <span class="req">*</span></label>
                        <input type="number" name="cook_time" value="{{ old('cook_time', $recipe->cook_time) }}" min="0"
                            placeholder="30" class="fi {{ $errors->has('cook_time') ? 'err' : '' }}" x-ref="cook_time">
                        @error('cook_time')<p class="err-msg">{{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label">Difficulty <span class="req">*</span></label>
                        <select name="difficulty" class="fs" x-ref="difficulty">
                            @foreach(['easy' => 'Light work', 'medium' => 'A bit of sweating', 'hard' => 'Culinary class wars level'] as $value => $label)
                                <option value="{{ $value }}" {{ old('difficulty', $recipe->difficulty) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label">Recipe Photo</label>
                    @if($recipe->image_path)
                        <div style="margin-bottom:0.75rem;">
                            <img src="{{ Storage::url($recipe->image_path) }}" class="current-image" alt="Current photo">
                            <p style="font-size:0.75rem; color:#999;">Upload a new photo to replace this one</p>
                        </div>
                    @endif
                    <div class="upload-zone">
                        <input type="file" name="image" accept="image/*"
                            @change="filename = $event.target.files[0]?.name ?? ''">
                        <span class="upload-icon"><span class="material-symbols-outlined">upload</span></span>
                        <p class="upload-text">Click to upload a photo</p>
                        <p class="upload-hint">JPEG, PNG, WebP — max 2 MB</p>
                        <p class="upload-filename" x-show="filename" x-text="filename"></p>
                    </div>
                    @error('image')<p class="err-msg">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Step 2: Categories --}}
            <div class="step-panel" :class="{ active: currentStep === 2 }">
                <h2 class="step-title">Categories</h2>
                <p class="step-desc">Help people find your recipe</p>

                <div class="cat-grid">
                    @php $ordering = ['complexity', 'status', 'type', 'protein', 'time']; @endphp
                    @foreach($ordering as $typeKey)
                        @if(isset($categoryTypes[$typeKey]))
                            @php $typeMeta = $categoryTypes[$typeKey]; @endphp
                            <div>
                                <label class="cat-type-label tl-{{ $typeKey }}">{{ $typeMeta['label'] }}</label>
                                <div class="category-options">
                                    <template x-for="category in allCategories.filter(c => c.type === '{{ $typeKey }}')" :key="category.id">
                                        <button type="button" class="cat-chip"
                                            :class="{ 'cat-chip-selected': selectedCategories.some(c => c.id === category.id) }"
                                            @click="toggleCategory(category)">
                                            <span x-text="category.name"></span>
                                            <span class="cat-chip-remove" x-show="selectedCategories.some(c => c.id === category.id)">✕</span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <template x-for="category in selectedCategories" :key="category.id">
                    <input type="hidden" name="categories[]" :value="category.id">
                </template>

                <p class="tags-hint">Click tags to select / deselect.</p>
                @error('categories')<p class="err-msg">{{ $message }}</p>@enderror
            </div>

            {{-- Step 3: Ingredients --}}
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
                        <div class="ing-row">
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

            {{-- Step 4: Steps --}}
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
                        <div class="step-row">
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

            {{-- Footer Nav --}}
            <div class="step-footer">
                <button type="button" class="btn-prev" @click="prevStep()" x-show="currentStep > 1">Back</button>
                <div x-show="currentStep === 1"></div>

                <span class="step-counter" x-text="`Step ${currentStep} of ${totalSteps}`"></span>

                <button type="button" class="btn-next" @click="nextStep()" x-show="currentStep < totalSteps">Continue →</button>
                <button type="submit" class="btn-save" x-show="currentStep === totalSteps">Update Recipe</button>
            </div>
        </form>

        {{-- Error Modal --}}
        <template x-if="showErrorModal">
            <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; z-index:9999;"
                @click.self="showErrorModal = false" @keydown.escape.window="showErrorModal = false">
                <div style="background:#fff; border-radius:1rem; border:1px solid #e5e5e5; box-shadow:0 8px 32px rgba(0,0,0,0.18); max-width:420px; width:90%; padding:2rem; text-align:center;" @click.stop>
                    <h3 style="margin:0 0 0.75rem; color:#c00; font-size:1.1rem; font-weight:700;">Please fix the following</h3>
                    <p style="margin:0 0 1.5rem; color:#444; font-size:0.95rem; line-height:1.5;" x-text="errorMessage"></p>
                    <button @click="showErrorModal = false"
                        style="background:#000; color:#fff; border:none; border-radius:0.5rem; padding:0.65rem 1.35rem; cursor:pointer; font-size:0.9rem; font-weight:600;">OK</button>
                </div>
            </div>
        </template>

    </div>
</div>

@endsection
