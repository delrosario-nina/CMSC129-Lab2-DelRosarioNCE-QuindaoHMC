@props([
    'name' => '',
])

@php
    // Deterministic color assignment based on tag name
    $colors = [
        'dinner'     => 'border-rose-400 text-rose-500 bg-rose-50',
        'lunch'      => 'border-orange-400 text-orange-500 bg-orange-50',
        'breakfast'  => 'border-yellow-400 text-yellow-600 bg-yellow-50',
        'quick'      => 'border-sky-400 text-sky-500 bg-sky-50',
        'cheap'      => 'border-emerald-400 text-emerald-600 bg-emerald-50',
        'healthy'    => 'border-green-400 text-green-600 bg-green-50',
        'snack'      => 'border-purple-400 text-purple-500 bg-purple-50',
        'dessert'    => 'border-pink-400 text-pink-500 bg-pink-50',
        'vegetarian' => 'border-lime-400 text-lime-600 bg-lime-50',
        'spicy'      => 'border-red-500 text-red-600 bg-red-50',
    ];

    $key   = strtolower(trim($name));
    $class = $colors[$key] ?? 'border-gray-400 text-gray-600 bg-gray-50';
@endphp

<span class="inline-block border rounded-full px-3 py-0.5 text-xs font-medium tracking-wide diary-tag {{ $class }}">
    {{ $name }}
</span>
