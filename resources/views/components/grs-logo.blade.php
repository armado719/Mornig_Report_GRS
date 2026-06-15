@props(['size' => 'md'])

@php
$sizes = [
    'sm'  => ['svg' => 'w-8 h-8',  'text' => 'text-lg', 'sub' => 'text-[9px]'],
    'md'  => ['svg' => 'w-12 h-12', 'text' => 'text-2xl', 'sub' => 'text-[10px]'],
    'lg'  => ['svg' => 'w-20 h-20', 'text' => 'text-4xl', 'sub' => 'text-xs'],
    'xl'  => ['svg' => 'w-28 h-28', 'text' => 'text-5xl', 'sub' => 'text-sm'],
];
$s = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center gap-1']) }}>
    {{-- Torre de perforación SVG --}}
    <svg class="{{ $s['svg'] }}" viewBox="0 0 64 72" fill="none" xmlns="http://www.w3.org/2000/svg">
        {{-- Corona (crown block) --}}
        <rect x="20" y="2" width="24" height="5" rx="1" fill="#6DBE6D"/>
        {{-- Piernas de la torre --}}
        <line x1="22" y1="7"  x2="10" y2="60" stroke="#6DBE6D" stroke-width="3" stroke-linecap="round"/>
        <line x1="42" y1="7"  x2="54" y2="60" stroke="#6DBE6D" stroke-width="3" stroke-linecap="round"/>
        {{-- Vigas horizontales --}}
        <line x1="16" y1="22" x2="48" y2="22" stroke="#2D7A4F" stroke-width="2"/>
        <line x1="13" y1="38" x2="51" y2="38" stroke="#2D7A4F" stroke-width="2"/>
        <line x1="11" y1="52" x2="53" y2="52" stroke="#2D7A4F" stroke-width="2"/>
        {{-- Vigas diagonales cruzadas --}}
        <line x1="16" y1="22" x2="48" y2="38" stroke="#1B4D35" stroke-width="1.5"/>
        <line x1="48" y1="22" x2="16" y2="38" stroke="#1B4D35" stroke-width="1.5"/>
        <line x1="13" y1="38" x2="51" y2="52" stroke="#1B4D35" stroke-width="1.5"/>
        <line x1="51" y1="38" x2="13" y2="52" stroke="#1B4D35" stroke-width="1.5"/>
        {{-- Base --}}
        <rect x="7"  y="60" width="50" height="5" rx="1" fill="#1B4D35"/>
        {{-- Kelly / drill string --}}
        <line x1="32" y1="7"  x2="32" y2="56" stroke="#6DBE6D" stroke-width="1.5" stroke-dasharray="4 3"/>
    </svg>

    {{-- Texto GRS --}}
    <div class="flex flex-col items-center leading-none">
        <span class="{{ $s['text'] }} font-black tracking-widest text-grs-verde font-mono">GRS</span>
        <span class="{{ $s['sub'] }} font-medium tracking-wider text-grs-texto uppercase">General Rigs Services</span>
    </div>
</div>
