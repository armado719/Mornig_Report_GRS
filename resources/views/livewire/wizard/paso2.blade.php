{{-- ══════════════════════════════════════════════════
     PASO 2 — Cronología de Operaciones
══════════════════════════════════════════════════ --}}

{{-- ── Resumen de horas ── --}}
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">

    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2 px-4 py-2 rounded-lg border
            {{ $totalHoras > 24 ? 'border-red-600 bg-red-900/20' : ($totalHoras == 24 ? 'border-grs-verde bg-grs-verde/10' : 'border-grs-borde bg-grs-primario') }}">
            <svg class="w-4 h-4 {{ $totalHoras > 24 ? 'text-red-400' : 'text-grs-verde' }}"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs text-grs-texto">Total</span>
            <span class="font-mono font-bold text-sm {{ $totalHoras > 24 ? 'text-red-400' : ($totalHoras == 24 ? 'text-grs-verde' : 'text-white') }}">
                {{ number_format($totalHoras, 2) }} / 24h
            </span>
        </div>

        @php
            $nocheTotal = collect($operaciones)->sum(fn($op) => (float)($op['noche_hrs'] ?? 0));
            $diaTotal   = collect($operaciones)->sum(fn($op) => (float)($op['dia_hrs'] ?? 0));
        @endphp

        <div class="hidden sm:flex items-center gap-3 text-xs">
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded bg-indigo-900/40 border border-indigo-800">
                <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                <span class="text-indigo-300">Noche</span>
                <span class="font-mono font-bold text-white">{{ number_format($nocheTotal, 1) }}h</span>
            </span>
            <span class="flex items-center gap-1.5 px-3 py-1.5 rounded bg-amber-900/40 border border-amber-800">
                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                <span class="text-amber-300">Día</span>
                <span class="font-mono font-bold text-white">{{ number_format($diaTotal, 1) }}h</span>
            </span>
        </div>
    </div>

    <div class="flex-1 max-w-xs hidden lg:block">
        <div class="h-2 bg-grs-borde rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all duration-300
                {{ $totalHoras > 24 ? 'bg-red-500' : 'bg-grs-verde' }}"
                style="width: {{ min(($totalHoras / 24) * 100, 100) }}%"></div>
        </div>
    </div>

    @error('operaciones')
        <p class="text-xs text-red-400 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.538-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>

{{-- ── Tabla de operaciones ── --}}
<div class="card-grs p-0 overflow-hidden">

    {{-- Cabecera — desktop --}}
    <div class="hidden lg:grid grid-cols-[3rem_6rem_6rem_5rem_11rem_1fr_7rem_4rem_4rem_3rem]
                gap-1 px-3 py-2 bg-grs-fondo border-b border-grs-borde">
        <span class="tabla-header text-center">#</span>
        <span class="tabla-header">Desde</span>
        <span class="tabla-header">Hasta</span>
        <span class="tabla-header text-center">Horas</span>
        <span class="tabla-header">Código</span>
        <span class="tabla-header">Descripción</span>
        <span class="tabla-header text-center">Turno</span>
        <span class="tabla-header text-center">Noche</span>
        <span class="tabla-header text-center">Día</span>
        <span class="tabla-header"></span>
    </div>

    <div class="divide-y divide-grs-borde">
        @foreach($operaciones as $i => $op)

            {{-- DESKTOP --}}
            <div class="hidden lg:grid grid-cols-[3rem_6rem_6rem_5rem_11rem_1fr_7rem_4rem_4rem_3rem]
                        gap-1 px-3 py-1.5 items-center hover:bg-grs-fondo/40 group transition-colors"
                 wire:key="op-{{ $i }}">

                {{-- Orden con flechas --}}
                <div class="flex flex-col items-center gap-0.5">
                    <button wire:click="moverFila({{ $i }}, 'up')"
                        class="text-gray-700 hover:text-grs-verde transition-colors {{ $i === 0 ? 'invisible' : '' }}">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                        </svg>
                    </button>
                    <span class="text-[10px] text-gray-600 font-mono">{{ $i + 1 }}</span>
                    <button wire:click="moverFila({{ $i }}, 'down')"
                        class="text-gray-700 hover:text-grs-verde transition-colors {{ $i === count($operaciones) - 1 ? 'invisible' : '' }}">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>

                <input type="time" wire:model.live="operaciones.{{ $i }}.hora_desde"
                    class="input-grs py-1 text-sm font-mono text-center px-1"/>

                <input type="time" wire:model.live="operaciones.{{ $i }}.hora_hasta"
                    class="input-grs py-1 text-sm font-mono text-center px-1"/>

                <div class="flex items-center justify-center">
                    <span class="font-mono text-sm font-bold
                        {{ ($op['horas'] ?? 0) > 12 ? 'text-yellow-400' : 'text-grs-verde' }}">
                        {{ isset($op['horas']) && $op['horas'] !== '' ? number_format((float)$op['horas'], 2) : '—' }}
                    </span>
                </div>

                <select wire:model="operaciones.{{ $i }}.codigo"
                    class="input-grs py-1 text-xs">
                    <option value="">— Código —</option>
                    @foreach($codigos as $cod => $desc)
                        <option value="{{ $cod }}">{{ $cod }} · {{ Str::limit($desc, 18) }}</option>
                    @endforeach
                </select>

                <input type="text" wire:model="operaciones.{{ $i }}.descripcion"
                    placeholder="Descripción de la actividad..."
                    class="input-grs py-1 text-sm"/>

                <select wire:model.live="operaciones.{{ $i }}.turno"
                    class="input-grs py-1 text-xs text-center
                           {{ ($op['turno'] ?? 'DIA') === 'NOCHE' ? 'text-indigo-300' : 'text-amber-300' }}">
                    <option value="DIA">☀️ Día</option>
                    <option value="NOCHE">🌙 Noche</option>
                </select>

                <div class="text-center">
                    <span class="font-mono text-xs text-indigo-400">
                        {{ ($op['turno'] ?? 'DIA') === 'NOCHE' && ($op['horas'] ?? '') !== ''
                            ? number_format((float)$op['horas'], 1) : '—' }}
                    </span>
                </div>

                <div class="text-center">
                    <span class="font-mono text-xs text-amber-400">
                        {{ ($op['turno'] ?? 'DIA') === 'DIA' && ($op['horas'] ?? '') !== ''
                            ? number_format((float)$op['horas'], 1) : '—' }}
                    </span>
                </div>

                <div class="flex justify-center">
                    <button wire:click="removeOperacion({{ $i }})"
                        class="opacity-0 group-hover:opacity-100 text-gray-600 hover:text-red-400
                               transition-all p-1 rounded">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- MOBILE / TABLET --}}
            <div class="lg:hidden p-3 space-y-3" wire:key="op-mobile-{{ $i }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-grs-verde uppercase tracking-wider">
                        Actividad #{{ $i + 1 }}
                    </span>
                    <button wire:click="removeOperacion({{ $i }})"
                        class="text-gray-600 hover:text-red-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="label-grs">Desde</label>
                        <input type="time" wire:model.live="operaciones.{{ $i }}.hora_desde"
                            class="input-grs text-sm font-mono text-center"/>
                    </div>
                    <div>
                        <label class="label-grs">Hasta</label>
                        <input type="time" wire:model.live="operaciones.{{ $i }}.hora_hasta"
                            class="input-grs text-sm font-mono text-center"/>
                    </div>
                    <div>
                        <label class="label-grs">Horas</label>
                        <div class="input-grs text-center font-mono font-bold text-grs-verde select-none">
                            {{ isset($op['horas']) && $op['horas'] !== '' ? number_format((float)$op['horas'], 2) : '—' }}
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="label-grs">Código</label>
                        <select wire:model="operaciones.{{ $i }}.codigo" class="input-grs text-xs">
                            <option value="">— Código —</option>
                            @foreach($codigos as $cod => $desc)
                                <option value="{{ $cod }}">{{ $cod }} · {{ Str::limit($desc, 16) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label-grs">Turno</label>
                        <select wire:model.live="operaciones.{{ $i }}.turno" class="input-grs text-sm">
                            <option value="DIA">☀️ Día</option>
                            <option value="NOCHE">🌙 Noche</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="label-grs">Descripción</label>
                    <input type="text" wire:model="operaciones.{{ $i }}.descripcion"
                        placeholder="Descripción de la actividad..."
                        class="input-grs text-sm"/>
                </div>
            </div>

        @endforeach
    </div>

    {{-- Fila de totales — desktop --}}
    <div class="hidden lg:grid grid-cols-[3rem_6rem_6rem_5rem_11rem_1fr_7rem_4rem_4rem_3rem]
                gap-1 px-3 py-2.5 bg-grs-fondo/80 border-t-2 border-grs-verde/30">
        <div></div>
        <div class="col-span-2">
            <span class="text-xs font-bold text-grs-texto uppercase tracking-wide">TOTAL</span>
        </div>
        <div class="text-center">
            <span class="font-mono font-bold text-sm
                {{ $totalHoras > 24 ? 'text-red-400' : ($totalHoras == 24 ? 'text-grs-verde' : 'text-white') }}">
                {{ number_format($totalHoras, 2) }}h
            </span>
        </div>
        <div></div><div></div><div></div>
        <div class="text-center">
            <span class="font-mono text-xs font-bold text-indigo-400">{{ number_format($nocheTotal, 1) }}h</span>
        </div>
        <div class="text-center">
            <span class="font-mono text-xs font-bold text-amber-400">{{ number_format($diaTotal, 1) }}h</span>
        </div>
        <div></div>
    </div>
</div>

{{-- ── Acciones ── --}}
<div class="flex items-center justify-between mt-4">
    <button wire:click="addOperacion"
        class="btn-grs-outline flex items-center gap-2 text-sm">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Agregar actividad
    </button>

    @if($totalHoras > 0 && $totalHoras < 24)
        <p class="text-xs text-yellow-400 flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Faltan {{ number_format(24 - $totalHoras, 2) }}h para completar las 24h
        </p>
    @elseif($totalHoras == 24)
        <p class="text-xs text-grs-verde flex items-center gap-1.5 font-medium">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Cronología completa — 24h registradas
        </p>
    @endif
</div>

{{-- ── Leyenda desplegable de códigos ── --}}
<details class="mt-4">
    <summary class="text-xs text-grs-texto cursor-pointer hover:text-grs-verde transition-colors
                    flex items-center gap-1.5 select-none">
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Ver tabla de códigos de operación
    </summary>
    <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-1.5">
        @foreach($codigos as $cod => $desc)
            <div class="flex items-start gap-2 px-2 py-1.5 rounded bg-grs-primario/50">
                <span class="font-mono text-xs font-bold text-grs-verde flex-shrink-0 w-5">{{ $cod }}</span>
                <span class="text-xs text-grs-texto leading-tight">{{ $desc }}</span>
            </div>
        @endforeach
    </div>
</details>
