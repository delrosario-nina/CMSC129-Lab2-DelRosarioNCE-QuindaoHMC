@extends('layouts.app')

@section('title', 'Nina\'s Recipe Diary')

@section('content')

    {{-- ── Recipe List ─────────────────────────────────────── --}}
    @if($recipes->isEmpty())
        <div class="text-center py-28 diary-body text-gray-500">
            <p class="text-lg mb-4">Your diary is empty.</p>
            <a href="{{ route('recipes.create') }}" class="btn-cook">Add your first recipe!</a>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @foreach($recipes as $recipe)
                @include('components.recipe-card-grid', ['recipe' => $recipe])
            @endforeach
        </div>

        <div class="mt-8 diary-body">
            {{ $recipes->withQueryString()->links() }}
        </div>
    @endif

@endsection
