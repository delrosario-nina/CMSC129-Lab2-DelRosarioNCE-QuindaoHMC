<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Recipe Diary')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-amber-50 min-h-screen">

    {{-- Navigation --}}
    <nav class="bg-white shadow-sm border-b border-amber-100">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('recipes.index') }}"
               class="text-2xl font-bold text-amber-600">
                📖 Recipe Diary
            </a>
            <div class="flex gap-4">
                <a href="{{ route('recipes.index') }}"
                   class="text-gray-600 hover:text-amber-600 transition">
                    My Recipes
                </a>
                <a href="{{ route('recipes.create') }}"
                   class="bg-amber-500 text-white px-4 py-2 rounded-lg hover:bg-amber-600 transition">
                    + New Recipe
                </a>
                <a href="{{ route('recipes.trash') }}"
                   class="text-gray-400 hover:text-red-500 transition">
                    🗑 Trash
                </a>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-6xl mx-auto px-4 mt-4">
            @include('components.flash-message', ['type' => 'success', 'message' => session('success')])
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-6xl mx-auto px-4 mt-4">
            @include('components.flash-message', ['type' => 'error', 'message' => session('error')])
        </div>
    @endif

    {{-- Main Content --}}
    <main class="max-w-6xl mx-auto px-4 py-8">
        @yield('content')
    </main>

</body>
</html>
