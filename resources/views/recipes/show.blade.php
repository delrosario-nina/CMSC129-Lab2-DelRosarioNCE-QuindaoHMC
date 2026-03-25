@extends('layouts.app')

@section('title', $recipe->title)

@section('content')

@php
    // Difficulty → star emojis
    $stars = match($recipe->difficulty) {
        'easy'   => '⭐',
        'medium' => '⭐⭐',
        'hard'   => '⭐⭐⭐',
        default  => $recipe->difficulty,
    };

    // Prev / next for arrow nav (simple: sibling records by id)
    $prev = \App\Models\Recipe::where('id', '<', $recipe->id)->orderBy('id', 'desc')->first();
    $next = \App\Models\Recipe::where('id', '>', $recipe->id)->orderBy('id', 'asc')->first();
@endphp

    {{-- ── Arrow navigation ──────────────────────────────── --}}
    @if($prev)
        <a href="{{ route('recipes.show', $prev) }}"
           class="fixed left-[-48px] top-[50vh] -translate-y-1/2 text-4xl text-gray-600 hover:text-gray-900 transition-all duration-300 hover:scale-110 select-none page-turn-left"
           style="left: -80px; top: 50vh;"
           title="Previous: {{ $prev->title }}">
            <span class="material-symbols-outlined">keyboard_arrow_left</span>
        </a>
    @endif
    @if($next)
        <a href="{{ route('recipes.show', $next) }}"
           class="fixed right-[-48px] top-[50vh] -translate-y-1/2 text-4xl text-gray-600 hover:text-gray-900 transition-all duration-300 hover:scale-110 select-none page-turn-right"
           style="right: -80px; top: 50vh;"
           title="Next: {{ $next->title }}">
            <span class="material-symbols-outlined">keyboard_arrow_right</span>
        </a>
    @endif

    {{-- ── Back link ──────────────────────────────────────── --}}
    <a href="{{ route('recipes.index') }}"
       class="diary-body text-sm text-gray-500 hover:text-gray-800 transition mb-8 inline-block">
        ← Back to diary
    </a>

    {{-- ── Hero row: info + image ─────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">

        {{-- Left: title, tags, meta, actions --}}
        <div class="flex flex-col justify-start">

            {{-- Title + edit pencil --}}
            <div class="flex items-start gap-3 mb-3">
                <h1 class="diary-title text-3xl leading-tight">{{ $recipe->title }}</h1>
                <a href="{{ route('recipes.edit', $recipe) }}"
                   class="text-gray-400 hover:text-gray-700 transition mt-1 shrink-0" title="Edit">
                    <span class="material-symbols-outlined">edit</span>
                </a>
                <form action="{{ route('recipes.destroy', $recipe) }}" method="POST"
                      onsubmit="return confirm('Move to trash?')" class="mt-1 shrink-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-600 transition" title="Delete">
                        <span class="material-symbols-outlined">delete</span>
                    </button>
                </form>
            </div>

            {{-- Tags --}}
            @if($recipe->categories->isNotEmpty())
                <div class="flex flex-wrap gap-1.5 mb-4">
                    @foreach($recipe->categories as $category)
                        @include('components.recipe-tag', ['name' => $category->name])
                    @endforeach
                </div>
            @endif

            {{-- Description --}}
            <p class="diary-body text-sm text-gray-600 mb-5 leading-relaxed">
                {{ $recipe->description }}
            </p>

            {{-- Meta --}}
            <div class="diary-body text-sm text-gray-700 space-y-1 mb-6">
                <p><span class="text-gray-400">Prep Time:</span> {{ $recipe->prep_time }} minutes</p>
                <p><span class="text-gray-400">Cooking Time:</span> {{ $recipe->cook_time }} minutes</p>
                <p><span class="text-gray-400">Difficulty:</span> {{ $stars }}</p>
            </div>

            {{-- CTA --}}
            <div class="flex items-center gap-3">
                <button id="step-view" class="btn-cook" type="button">Step-by-step</button>
            </div>
        </div>

        {{-- Right: recipe image --}}
        <div>
            @if($recipe->image_path)
                <img src="{{ Storage::url($recipe->image_path) }}"
                     alt="{{ $recipe->title }}"
                     class="w-full h-72 md:h-full object-cover rounded-2xl border-2 border-gray-900"
                     style="max-height:340px;">
            @else
                <div class="w-full rounded-2xl border-2 border-gray-900 bg-amber-50 flex items-center justify-center"
                     style="height:300px;">
                    <span class="text-7xl">🍽️</span>
                </div>
            @endif
        </div>
    </div>

    {{-- ── Ingredients + Process ───────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="process">

        {{-- Ingredients --}}
        <div class="section-box">
            <div class="section-box-header">
                Ingredients
                <span class="font-normal text-gray-500 text-xs">({{ $recipe->ingredients->count() }})</span>
            </div>
            <div class="section-box-body">
                @if($recipe->ingredients->isEmpty())
                    <p class="diary-body text-sm text-gray-400">No ingredients added yet.</p>
                @else
                    <ul class="space-y-2 diary-body text-sm">
                        @foreach($recipe->ingredients as $ingredient)
                            <li class="flex gap-2 items-start">
                                <span class="text-gray-400 shrink-0">–</span>
                                <span>
                                    <span class="font-bold">{{ $ingredient->amount }}</span>
                                    {{ $ingredient->name }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Process / Steps --}}
        <div class="section-box">
            <div class="section-box-header">Process</div>
            <div class="section-box-body">
                @if($recipe->steps->isEmpty())
                    <p class="diary-body text-sm text-gray-400">No steps added yet.</p>
                @else
                    <div class="space-y-5 diary-body text-sm">
                        @foreach($recipe->steps as $step)
                            <div>
                                <p class="font-bold mb-1">Step {{ $step->order }}: {{ $step->title }}</p>
                                <p class="text-gray-600 leading-relaxed">{{ $step->instruction }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Step-by-step full-screen modal (refined) --}}
    <div id="step-modal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
        <div class="bg-white w-full h-full max-w-full max-h-full overflow-hidden">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <div class="flex flex-col">
                    <h1 class="text-3xl font-bold" style="font-family: 'Kiwisoda', sans-serif;">{{ $recipe->title }}</h1>
                    <p class="text-sm text-gray-500">Step <span id="step-number">1</span> of {{ $recipe->steps->count() }}</p>
                </div>
                <button id="step-modal-close" class="text-2xl font-bold px-2">✕</button>
            </div>
            <div class="p-8 h-[calc(100vh-132px)] overflow-y-auto" id="step-content"></div>
            <div class="flex items-center justify-between p-4 border-t border-gray-200">
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
                stepNumber.textContent = currentStep + 1;
                stepContent.innerHTML = '<h3 class="text-2xl font-bold mb-3">Step ' + step.order + ': ' + step.title + '</h3>' +
                    '<p class="text-gray-700 leading-relaxed">' + step.instruction.replace(/\n/g, '<br/>') + '</p>';

                prevBtn.disabled = currentStep === 0;
                nextBtn.disabled = currentStep === steps.length - 1;
            }

            function toggleModal(show) {
                if (show) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    currentStep = 0;
                    renderStep();
                } else {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
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
