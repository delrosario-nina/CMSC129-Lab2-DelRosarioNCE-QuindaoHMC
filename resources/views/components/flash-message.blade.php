@props([
    'type' => 'success',
    'message' => '',
    'modal' => false,
    'confirm' => false,
    'confirmText' => 'Yes',
    'cancelText' => 'Cancel',
    'title' => null,
])

@php
    $bannerStyles = [
        'success' => 'border-emerald-400 bg-emerald-50 text-emerald-800',
        'error'   => 'border-red-400 bg-red-50 text-red-800',
        'warning' => 'border-amber-400 bg-amber-50 text-amber-800',
        'info'    => 'border-sky-400 bg-sky-50 text-sky-800',
    ];
    $bannerStyle = $bannerStyles[$type] ?? $bannerStyles['success'];
    $modalTitle  = $title ?: ucfirst($type);
@endphp

@if($modal)
    {{-- x-data lives on a plain div, x-if lives on a <template> inside it --}}
    <div x-data="{ show: true }">
        <template x-if="show">
            <div
                style="position:fixed; inset:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; z-index:9999;"
                @keydown.escape.window="show = false; $dispatch('flashModalClose')"
                @click.self="show = false; $dispatch('flashModalClose')"
            >
                <div style="background:#fff; border-radius:1rem; border:1px solid #e5e5e5; box-shadow:0 8px 32px rgba(0,0,0,0.18); max-width:420px; width:90%; padding:2rem; text-align:center;" @click.stop>
                    <h3 style="margin:0 0 0.75rem; color:#111; font-size:1.1rem; font-weight:700;">{{ $modalTitle }}</h3>
                    <p style="margin:0 0 1.5rem; color:#444; font-size:0.95rem; line-height:1.5;">{{ $message }}</p>
                    @if($confirm)
                        <div style="display:flex; justify-content:center; gap:0.75rem;">
                            <button
                                @click="show = false; $dispatch('flashConfirm')"
                                style="background:#000; color:#fff; border:none; border-radius:0.5rem; padding:0.65rem 1.35rem; cursor:pointer; font-size:0.9rem; font-weight:600;"
                            >{{ $confirmText }}</button>
                            <button
                                @click="show = false; $dispatch('flashCancel')"
                                style="background:#f5f5f5; color:#333; border:1px solid #dcdcdc; border-radius:0.5rem; padding:0.65rem 1.35rem; cursor:pointer; font-size:0.9rem; font-weight:600;"
                            >{{ $cancelText }}</button>
                        </div>
                    @else
                        <button
                            @click="show = false; $dispatch('flashModalClose')"
                            style="background:#000; color:#fff; border:none; border-radius:0.5rem; padding:0.65rem 1.35rem; cursor:pointer; font-size:0.9rem; font-weight:600;"
                        >OK</button>
                    @endif
                </div>
            </div>
        </template>
    </div>
@else
    <div x-data="{ show: true }" x-show="show" x-cloak
         class="border rounded-lg px-4 py-3 text-sm diary-body flex justify-between items-center {{ $bannerStyle }}">
        <span class="flex-1">{{ $message }}</span>
        <button @click="show = false" class="ml-4 opacity-60 hover:opacity-100 transition">✕</button>
    </div>
@endif
