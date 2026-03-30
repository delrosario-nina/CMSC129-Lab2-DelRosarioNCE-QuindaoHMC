@extends('layouts.app')

@section('title', $recipe->title)

@section('content')

<style>
.recipe-page { font-family: 'DM Sans', sans-serif; max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 1.5rem 1.5rem; }
.recipe-page .back-link { display: inline-block; margin-bottom: 1rem; color: #444; font-weight: 500; }
.recipe-hero { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; }
@media (max-width: 900px) { .recipe-hero { grid-template-columns: 1fr; } }
.recipe-title { font-family: 'Kiwisoda', sans-serif; font-size: 3rem; margin: 0 0 0.5rem; line-height: 1.1; }
.recipe-tags { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem; }
.recipe-tag { border-radius: 999px; border: 1px solid #000; padding: 0.25rem 0.8rem; font-size: 0.8rem; font-weight: 700; background: #fefff0; }
.recipe-description { font-size: 1.2rem; margin-bottom: 1.25rem; color: #111; max-width: 80%; }
.recipe-meta { font-size: 1rem; color: #333; margin-bottom: 1.25rem; }
.recipe-meta p { margin: 0.18rem 0; }
.btn-cook { background: #7dff5f; color: #000; border: none; border-radius: 999px; padding: 0.8rem 1.6rem; font-weight: 700; cursor: pointer; transition: transform .2s ease; }
.btn-cook:hover { transform: translateY(-1px); background: #57d63f; }
.recipe-image { width: 100%; border: 2px solid #000; border-radius: 1rem; object-fit: cover; height: 340px; }
.section-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1.25rem; }
@media (max-width: 900px) { .section-row { grid-template-columns: 1fr; } }
.section-box { border: 2px solid #000; border-radius: 1rem; overflow: hidden; }
.section-box.process { border: none; }
.section-heading { background: #fff; border-bottom: 2px solid #000; padding: 0.75rem 1rem; font-weight: 800; font-size: 1.25rem; }
.section-box.process .section-heading { border-bottom: none; }
.section-body { padding: 1rem; }
.sidebar-indicator { position: absolute; left: -25px; top: 50%; transform: translateY(-50%); font-size: 2.5rem; color: #000; }
</style>

@php
    // Difficulty → star emojis
    $stars = match($recipe->difficulty) {
        'easy'   => '★✩✩ Light Work',
        'medium' => '★★✩ A Bit of Sweating',
        'hard'   => '★★★ Culinary Class Wars Level',
        default  => $recipe->difficulty,
    };

    $difficultyStyle = match($recipe->difficulty) {
        'easy'   => 'background-color: #dcfce7; color: #166534; border: 1.5px solid #86efac;',
        'medium' => 'background-color: #fef9c3; color: #854d0e; border: 1.5px solid #fde047;',
        'hard'   => 'background-color: #fee2e2; color: #991b1b; border: 1.5px solid #fca5a5;',
        default  => 'background-color: #f3f4f6; color: #374151;',
    };

    // Prev / next for arrow nav (simple: sibling records by id)
    $prev = \App\Models\Recipe::where('id', '<', $recipe->id)->orderBy('id', 'desc')->first();
    $next = \App\Models\Recipe::where('id', '>', $recipe->id)->orderBy('id', 'asc')->first();
@endphp


    <div class="recipe-page">
        <a href="{{ route('recipes.index') }}" class="back-link">← Back to diary</a>

        <div class="recipe-hero">
            <div>
                <div class="flex items-center gap-4 mb-3">
                    <h1 class="recipe-title">{{ $recipe->title }}</h1>
                    <a href="{{ route('recipes.edit', $recipe) }}" class="text-gray-700 hover:text-black" title="Edit">
                        <span class="material-symbols-outlined">edit</span>
                    </a>
                    <div x-data="{ showDeleteConfirm: false }"
                         @flashConfirm="$refs.deleteRecipeForm && $refs.deleteRecipeForm.submit(); showDeleteConfirm=false"
                         @flashCancel="showDeleteConfirm=false"
                         class="inline">

                        <button type="button" @click="showDeleteConfirm=true" class="text-red-500 hover:text-red-700" title="Delete">
                            <span class="material-symbols-outlined">delete</span>
                        </button>

                        <form id="deleteRecipeForm" x-ref="deleteRecipeForm" action="{{ route('recipes.destroy', $recipe) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>

                        <template x-if="showDeleteConfirm">
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
                    </div>
                </div>

                <div class="recipe-tags">
                    @foreach($recipe->categories as $category)
                        <span class="recipe-tag">{{ $category->name }}</span>
                    @endforeach
                </div>

                <p class="recipe-description">{{ $recipe->description }}</p>

                <div class="recipe-meta">
                    <p><strong>Prep Time:</strong> {{ $recipe->prep_time }} minutes</p>
                    <p><strong>Cooking Time:</strong> {{ $recipe->cook_time }} minutes</p>
                    <p class="flex items-center gap-2">
                        <span><strong>Difficulty:</strong></span>
                        <span class="font-bold px-3 py-1 rounded-full" style="{{ $difficultyStyle }}"> {{ $stars }} </span>
                    </p>
                </div>

                <button id="step-view" class="btn-cook" type="button">Let’s Cook!</button>
            </div>

            <div>
                @if($recipe->image_path)
                    <img src="{{ Storage::url($recipe->image_path) }}" alt="{{ $recipe->title }}" class="recipe-image" />
                @else
                    <div class="recipe-image flex items-center justify-center text-5xl">🍽️</div>
                @endif
            </div>
        </div>

        <div class="section-row" id="process">
            <div class="section-box">
                <div class="section-heading">Ingredients ({{ $recipe->ingredients->count() }})</div>
                <div class="section-body">
                    @if($recipe->ingredients->isEmpty())
                        <p class="text-gray-500">No ingredients added yet.</p>
                    @else
                        <ul class="space-y-2">
                            @foreach($recipe->ingredients as $ingredient)
                                <li><strong>{{ $ingredient->amount }}</strong> {{ $ingredient->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <div class="section-box process">
                <div class="section-heading">Process</div>
                <div class="section-body">
                    @if($recipe->steps->isEmpty())
                        <p class="text-gray-500">No steps added yet.</p>
                    @else
                        <ol class="space-y-4">
                            @foreach($recipe->steps as $step)
                                <li>
                                    <p class="font-bold">Step {{ $step->order }}: {{ $step->title }}</p>
                                    <p class="text-gray-700">{!! nl2br(e($step->instruction)) !!}</p>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>
        </div>



    <script>
        // Global fallback for flash-message confirm event.
        window.addEventListener('flashConfirm', function() {
            const form = document.getElementById('deleteRecipeForm');
            if (form) {
                form.submit();
            }
        });
    </script>

    {{-- Step-by-step full-screen modal (refined) --}}
    <div id="step-modal" class="fixed inset-0 bg-black/80 hidden flex items-center justify-center z-50">
        <div class="bg-white w-11/12 max-w-5xl h-[90vh] overflow-hidden flex flex-col">
            {{-- Header --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-200 shrink-0">
                <div class="flex flex-col">
                    <h1 class="text-3xl font-bold" style="font-family: 'Kiwisoda', sans-serif;">{{ $recipe->title }}</h1>
                    <p class="text-sm text-gray-500">Step <span id="step-number">1</span> of {{ $recipe->steps->count() }}</p>
                </div>
                <button id="step-modal-close" class="text-2xl font-bold px-2">✕</button>
            </div>

            {{-- Content (fills remaining space) --}}
            <div class="flex-1 overflow-y-auto p-8" id="step-content"></div>

            {{-- Footer buttons (always at bottom) --}}
            <div class="flex items-center justify-between p-4 border-t border-gray-200 shrink-0">
                <button id="step-prev" class="btn-cook bg-gray-900 text-white">Previous</button>
                <button id="step-next" class="btn-cook">Next</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var steps = @json($recipe->steps->sortBy('order')->values()->map(function ($step) {
                return ['order' => $step->order, 'title' => $step->title, 'instruction' => $step->instruction];
            }));

            var currentStep = 0;
            var modal = document.getElementById('step-modal');
            var openBtn = document.getElementById('step-view');
            var closeBtn = document.getElementById('step-modal-close');
            var stepNumber = document.getElementById('step-number');
            var stepContent = document.getElementById('step-content');
            var prevBtn = document.getElementById('step-prev');
            var nextBtn = document.getElementById('step-next');

            function renderStep() {
                if (!steps.length) {
                    stepContent.innerHTML = '<p class="text-gray-500">No steps available.</p>';
                    stepNumber.textContent = '0';
                    prevBtn.disabled = true;
                    nextBtn.disabled = true;
                    return;
                }

                var step = steps[currentStep];
                var isFirstStep = currentStep === 0;
                var isLastStep = currentStep === steps.length - 1;

                stepNumber.textContent = currentStep + 1;
                stepContent.innerHTML = '<h3 class="text-2xl font-bold mb-3">Step ' + step.order + ': ' + step.title + '</h3>' +
                    '<p class="text-gray-700 leading-relaxed">' + step.instruction.replace(/\n/g, '<br/>') + '</p>';

                // Update previous button
                prevBtn.disabled = isFirstStep;
                if (isFirstStep) {
                    prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    prevBtn.textContent = 'Previous';
                } else {
                    prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    var prevStep = steps[currentStep - 1];
                    prevBtn.textContent = 'Previous: ' + prevStep.title;
                }

                // Update next button
                if (isLastStep) {
                    nextBtn.disabled = false;
                    nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    nextBtn.textContent = 'Finish';
                } else {
                    nextBtn.disabled = false;
                    nextBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    var nextStep = steps[currentStep + 1];
                    nextBtn.textContent = 'Next: ' + nextStep.title;
                }
            }

            function toggleModal(show) {
                if (show) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                    currentStep = 0;
                    renderStep();
                } else {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                }
            }

            if (openBtn) {
                openBtn.addEventListener('click', function () {
                    toggleModal(true);
                });
            }
            if (closeBtn) {
                closeBtn.addEventListener('click', function () {
                    toggleModal(false);
                });
            }
            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    if (currentStep > 0) {
                        currentStep -= 1;
                        renderStep();
                    }
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    if (currentStep < steps.length - 1) {
                        currentStep += 1;
                        renderStep();
                    } else if (currentStep === steps.length - 1) {
                        // Close modal on "Finish"
                        toggleModal(false);
                    }
                });
            }

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    toggleModal(false);
                }
            });
        });
    </script>

@endsection
