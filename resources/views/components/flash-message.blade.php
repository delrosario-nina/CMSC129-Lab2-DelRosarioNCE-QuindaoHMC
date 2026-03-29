@props(['type' => 'success', 'message' => ''])

@php
    $styles = [
        'success' => 'border-emerald-400 bg-emerald-50 text-emerald-800',
        'error'   => 'border-red-400 bg-red-50 text-red-800',
    ];
    $style = $styles[$type] ?? $styles['success'];
@endphp

<div x-data="{ show: true }" x-show="show"
     class="border rounded-lg px-4 py-3 text-sm diary-body flex justify-between items-center {{ $style }}">
    <span>{{ $message }}</span>
    <button @click="show = false" class="ml-4 opacity-60 hover:opacity-100 transition">✕</button>
</div>
