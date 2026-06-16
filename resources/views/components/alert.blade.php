@props([
    'type'        => 'info',   // success | error | warning | info
    'title'       => null,
    'dismissible' => false,
])

@php
$styles = [
    'success' => [
        'wrap'  => 'bg-grs-verde/10 border-grs-verde/40 text-grs-verde',
        'icon'  => '#6DBE6D',
        'path'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    ],
    'error' => [
        'wrap'  => 'bg-red-500/10 border-red-500/40 text-red-400',
        'icon'  => '#EF4444',
        'path'  => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    ],
    'warning' => [
        'wrap'  => 'bg-yellow-500/10 border-yellow-500/40 text-yellow-400',
        'icon'  => '#F59E0B',
        'path'  => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    ],
    'info' => [
        'wrap'  => 'bg-blue-500/10 border-blue-500/40 text-blue-400',
        'icon'  => '#60A5FA',
        'path'  => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ],
];
$s = $styles[$type] ?? $styles['info'];
@endphp

<div x-data="{ show: true }" x-show="show" x-transition
     class="flex items-start gap-3 px-4 py-3 rounded-xl border text-sm {{ $s['wrap'] }}">

    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:{{ $s['icon'] }}">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['path'] }}"/>
    </svg>

    <div class="flex-1 min-w-0">
        @if($title)
            <p class="font-semibold mb-0.5">{{ $title }}</p>
        @endif
        <div class="leading-relaxed">{{ $slot }}</div>
    </div>

    @if($dismissible)
    <button @click="show = false" class="flex-shrink-0 opacity-60 hover:opacity-100 transition-opacity mt-0.5">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    @endif
</div>
