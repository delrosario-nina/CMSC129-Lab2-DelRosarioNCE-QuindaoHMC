@extends('layouts.app')

@section('title', 'Edit ' . $recipe->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('recipes.show', $recipe) }}"
           class="text-amber-500 hover:text-amber-600">← Back to Recipe</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-amber-100 p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-8">Edit Recipe</h1>

        <form action="{{ route('recipes.update', $recipe) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Basic Info --}}
            <div class="grid grid-cols-1 gap-6 mb-8">

                {{-- Title --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recipe Title *</label>
                    <input type="text" name="title" value="{{ old('title', $recipe->title) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 @error('title') border-red-400 @enderror">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400 @error('description') border-red-400 @enderror">{{ old('description', $recipe->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Time & Difficulty --}}
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prep Time (min) *</label>
                        <input type="number" name="prep_time" value="{{ old('prep_time', $recipe->prep_time) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cook Time (min) *</label>
                        <input type="number" name="cook_time" value="{{ old('cook_time', $recipe->cook_time) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Difficulty *</label>
                        <select name="difficulty"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
                            @foreach(['easy', 'medium', 'hard'] as $level)
                                <option value="{{ $level }}"
                                    {{ old('difficulty', $recipe->difficulty) == $level ? 'selected' : '' }}>
                                    {{ ucfirst($level) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Image --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recipe Image</label>
                    @if($recipe->image_path)
                        <img src="{{ Storage::url($recipe->image_path) }}"
                             class="w-32 h-32 object-cover rounded-lg mb-2">
                        <p class="text-xs text-gray-400 mb-2">Upload a new image to replace the current one</p>
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Categories --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach($categories as $category)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                       {{ $recipe->categories->contains($category->id) ? 'checked' : '' }}
                                       class="rounded text-amber-500">
                                <span class="text-sm text-gray-600">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Ingredients --}}
            <div class="mb-8"
                 x-data="{ ingredients: {{ $recipe->ingredients->map(fn($i) => ['name' => $i->name, 'amount' => $i->amount])->toJson() }} }">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-lg font-bold text-gray-700">Ingredients *</h2>
                    <button type="button" @click="ingredients.push({ name: '', amount: '' })"
                            class="text-sm bg-amber-100 text-amber-700 px-3 py-1 rounded-lg hover:bg-amber-200 transition">
                        + Add Ingredient
                    </button>
                </div>
                <div class="space-y-3">
                    <template x-for="(ingredient, index) in ingredients" :key="index">
                        <div class="flex gap-3 items-center">
                            <input type="text" :name="`ingredients[${index}][amount]`"
                                   placeholder="Amount" x-model="ingredient.amount"
                                   class="w-1/3 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
                            <input type="text" :name="`ingredients[${index}][name]`"
                                   placeholder="Ingredient name" x-model="ingredient.name"
                                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
                            <button type="button" @click="ingredients.splice(index, 1)"
                                    x-show="ingredients.length > 1"
                                    class="text-red-400 hover:text-red-600">✕</button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Steps --}}
            <div class="mb-8"
                 x-data="{ steps: {{ $recipe->steps->map(fn($s) => ['title' => $s->title, 'instruction' => $s->instruction])->toJson() }} }">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-lg font-bold text-gray-700">Steps *</h2>
                    <button type="button" @click="steps.push({ title: '', instruction: '' })"
                            class="text-sm bg-amber-100 text-amber-700 px-3 py-1 rounded-lg hover:bg-amber-200 transition">
                        + Add Step
                    </button>
                </div>
                <div class="space-y-4">
                    <template x-for="(step, index) in steps" :key="index">
                        <div class="flex gap-3">
                            <div class="w-8 h-8 bg-amber-500 text-white rounded-full flex items-center justify-center font-bold shrink-0 mt-2"
                                 x-text="index + 1"></div>
                            <div class="flex-1 space-y-2">
                                <input type="text" :name="`steps[${index}][title]`"
                                       placeholder="Step title" x-model="step.title"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
                                <textarea :name="`steps[${index}][instruction]`"
                                          placeholder="Instructions..." x-model="step.instruction" rows="2"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400"></textarea>
                            </div>
                            <button type="button" @click="steps.splice(index, 1)"
                                    x-show="steps.length > 1"
                                    class="text-red-400 hover:text-red-600 mt-2">✕</button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3">
                <button type="submit"
                        class="bg-amber-500 text-white px-8 py-3 rounded-lg hover:bg-amber-600 transition font-medium">
                    Update Recipe
                </button>
                <a href="{{ route('recipes.show', $recipe) }}"
                   class="border border-gray-300 text-gray-600 px-8 py-3 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>

        </form>
    </div>
@endsection
