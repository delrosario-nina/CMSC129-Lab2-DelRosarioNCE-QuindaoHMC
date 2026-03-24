@extends('layouts.app')

@section('title', 'Nina\'s Recipe Diary')

@section('content')

    {{-- ── Recipe List ─────────────────────────────────────── --}}
    @if($recipes->isEmpty())
        <div class="text-center py-28 diary-body text-gray-500">
            <p class="text-5xl mb-5">📭</p>
            <p class="text-lg mb-4">Your diary is empty.</p>
            <a href="{{ route('recipes.create') }}" class="btn-cook">Add your first recipe!</a>
        </div>
    @else
        <div class="divide-y divide-gray-200">
            @foreach($recipes as $i => $recipe)
                @include('components.recipe-card', [
                    'recipe' => $recipe,
                    'page'   => $loop->iteration,
                ])
            @endforeach
        </div>

        <div class="mt-8 diary-body">
            {{ $recipes->withQueryString()->links() }}
        </div>
    @endif

@endsection
