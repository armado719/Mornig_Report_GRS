{{-- ══════════════════════════════════════════════════
     PASO 4 — Estado del Equipo
     BHA+Broca · Inventario Tubería · Top Drive · Equipos Reparación
══════════════════════════════════════════════════ --}}

{{-- Layout 2 columnas en desktop: BHA + Top Drive --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

    {{-- ── BHA + Broca ── --}}
    <div class="card-grs">
        <h3 class="seccion-titulo">
            <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
            BHA + Broca
        </h3>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="label-grs">No. Broca</label>
                <input type="text" wire:model="bha_numero"
                    placeholder="Ej: 3"
                    class="input-grs font-mono text-center"/>
            </div>
            <div>
                <label class="label-grs">Tamaño</label>
                <input type="text" wire:model="bha_tamano"
                    placeholder='Ej: 8½"'
                    class="input-grs font-mono text-center"/>
            </div>
            <div>
                <label class="label-grs">Tipo</label>
                <input type="text" wire:model="bha_tipo"
                    placeholder="Ej: PDC, TCI, Tricono..."
                    class="input-grs"/>
            </div>
            <div>
                <label class="label-grs">Jets (tamaño)</label>
                <input type="text" wire:model="bha_jets"
                    placeholder="Ej: 3×12"
                    class="input-grs font-mono text-center"/>
            </div>
            <div>
                <label class="label-grs">Serie</label>
                <input type="text" wire:model="bha_serie"
                    placeholder="Número de serie"
                    class="input-grs font-mono"/>
            </div>
            <div>
                <label class="label-grs">Total BHA</label>
                <div class="relative">
                    <input type="number" wire:model="bha_total_bha"
                        step="0.01" min="0" placeholder="0.00"
                        inputmode="decimal"
                        class="input-grs font-mono text-center pr-8"/>
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-600">ft</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Top Drive ── --}}
    <div class="card-grs">
        <h3 class="seccion-titulo">
            <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Top Drive
        </h3>

        <div class="space-y-3">
            @php
            $camposTD = [
                ['key' => 'td_hrs_rotacion',  'label' => 'Horas Rotación',    'unit' => 'h'],
                ['key' => 'td_hrs_unidad',    'label' => 'Horas Unidad',      'unit' => 'h'],
                ['key' => 'td_hrs_motor',     'label' => 'Horas Motor',       'unit' => 'h'],
                ['key' => 'td_acum_rotacion', 'label' => 'Acum. Rotación',    'unit' => 'h'],
            ];
            @endphp

            @foreach($camposTD as $c)
            <div class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg bg-grs-fondo/40 border border-grs-borde">
                <label class="text-sm font-medium text-grs-texto">{{ $c['label'] }}</label>
                <div class="flex items-center gap-2">
                    <input type="number"
                        wire:model="{{ $c['key'] }}"
                        step="0.01" min="0" placeholder="0.00"
                        inputmode="decimal"
                        class="input-grs font-mono text-right text-sm w-28"/>
                    <span class="text-xs text-gray-600 w-4">{{ $c['unit'] }}</span>
                </div>
            </div>
            @endforeach

            {{-- Resumen visual --}}
            @if($td_hrs_rotacion || $td_hrs_unidad)
            <div class="pt-2 border-t border-grs-borde">
                @php
                    $rotPct = $td_hrs_unidad > 0
                        ? min(100, (float)$td_hrs_rotacion / (float)$td_hrs_unidad * 100)
                        : 0;
                @endphp
                <div class="flex justify-between text-xs text-grs-texto mb-1">
                    <span>Eficiencia rotación</span>
                    <span class="font-mono font-bold text-grs-verde">{{ number_format($rotPct, 1) }}%</span>
                </div>
                <div class="h-1.5 bg-grs-borde rounded-full overflow-hidden">
                    <div class="h-full bg-grs-verde rounded-full transition-all duration-500"
                         style="width: {{ $rotPct }}%"></div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ── Inventario de Tubería ── --}}
<div class="card-grs mb-4">
    <div class="flex items-center justify-between mb-3">
        <h3 class="seccion-titulo mb-0">
            <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
            </svg>
            Inventario de Tubería
        </h3>
        <button wire:click="addInventario" class="btn-grs-outline text-xs flex items-center gap-1.5 py-1.5">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Agregar fila
        </button>
    </div>

    {{-- Cabecera --}}
    <div class="hidden sm:grid grid-cols-[1fr_6rem_8rem_6rem_6rem_3rem] gap-2 px-2 mb-1">
        <span class="tabla-header">Diámetro / Descripción</span>
        <span class="tabla-header text-center">Torre</span>
        <span class="tabla-header text-center">Base / Reparación</span>
        <span class="tabla-header text-center">Locación</span>
        <span class="tabla-header text-center font-bold text-grs-verde">Total</span>
        <span class="tabla-header"></span>
    </div>

    <div class="space-y-1.5">
        @forelse($inventario as $i => $inv)
        <div class="grid grid-cols-1 sm:grid-cols-[1fr_6rem_8rem_6rem_6rem_3rem] gap-2 items-center
                    px-2 py-1.5 rounded-lg hover:bg-grs-fondo/30 group transition-colors"
             wire:key="inv-{{ $i }}">

            <div>
                <label class="label-grs sm:hidden">Diámetro</label>
                <input type="text" wire:model="inventario.{{ $i }}.diametro"
                    placeholder='Ej: 5" DP NC-50'
                    class="input-grs text-sm font-mono"/>
            </div>
            <div>
                <label class="label-grs sm:hidden">Torre</label>
                <input type="number" wire:model.live="inventario.{{ $i }}.torre"
                    min="0" inputmode="numeric" placeholder="0"
                    class="input-grs text-sm font-mono text-center"/>
            </div>
            <div>
                <label class="label-grs sm:hidden">Base / Reparación</label>
                <input type="number" wire:model.live="inventario.{{ $i }}.base_reparacion"
                    min="0" inputmode="numeric" placeholder="0"
                    class="input-grs text-sm font-mono text-center"/>
            </div>
            <div>
                <label class="label-grs sm:hidden">Locación</label>
                <input type="number" wire:model.live="inventario.{{ $i }}.locacion"
                    min="0" inputmode="numeric" placeholder="0"
                    class="input-grs text-sm font-mono text-center"/>
            </div>
            <div class="flex items-center justify-center">
                <span class="font-mono font-bold text-grs-verde text-sm">
                    {{ (int)($inv['torre'] ?? 0) + (int)($inv['base_reparacion'] ?? 0) + (int)($inv['locacion'] ?? 0) }}
                </span>
            </div>
            <div class="flex justify-center">
                <button wire:click="removeInventario({{ $i }})"
                    class="opacity-0 group-hover:opacity-100 text-gray-600 hover:text-red-400
                           transition-all p-1 rounded">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        @empty
        <div class="text-center py-6 text-grs-texto text-sm">
            No hay filas. Haz clic en "Agregar fila" para comenzar.
        </div>
        @endforelse
    </div>
</div>

{{-- ── Equipos en Reparación ── --}}
<div class="card-grs">
    <div class="flex items-center justify-between mb-3">
        <h3 class="seccion-titulo mb-0">
            <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Equipos en Reparación
        </h3>
        <button wire:click="addEquipo" class="btn-grs-outline text-xs flex items-center gap-1.5 py-1.5">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Agregar equipo
        </button>
    </div>

    @forelse($equipos as $i => $eq)
    <div class="mb-3 p-3 rounded-lg bg-grs-fondo/40 border border-grs-borde group"
         wire:key="eq-{{ $i }}">

        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-yellow-400 uppercase tracking-wider">
                Equipo {{ $i + 1 }}
            </span>
            <button wire:click="removeEquipo({{ $i }})"
                class="text-gray-600 hover:text-red-400 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="lg:col-span-2">
                <label class="label-grs">Equipo / Descripción</label>
                <input type="text" wire:model="equipos.{{ $i }}.equipo"
                    placeholder="Nombre del equipo..."
                    class="input-grs text-sm"/>
            </div>
            <div>
                <label class="label-grs">Días en reparación</label>
                <input type="number" wire:model="equipos.{{ $i }}.dias"
                    min="0" inputmode="numeric" placeholder="0"
                    class="input-grs text-sm font-mono text-center"/>
            </div>
            <div>
                <label class="label-grs">Estado</label>
                <select wire:model="equipos.{{ $i }}.estado" class="input-grs text-sm">
                    <option value="">— Estado —</option>
                    <option value="En reparación">En reparación</option>
                    <option value="Pendiente repuesto">Pendiente repuesto</option>
                    <option value="Reparado">Reparado</option>
                    <option value="Fuera de servicio">Fuera de servicio</option>
                </select>
            </div>
            <div>
                <label class="label-grs">Motivo</label>
                <input type="text" wire:model="equipos.{{ $i }}.motivo"
                    placeholder="Causa de la falla..."
                    class="input-grs text-sm"/>
            </div>
            <div class="sm:col-span-2 lg:col-span-5">
                <label class="label-grs">Comentarios adicionales</label>
                <input type="text" wire:model="equipos.{{ $i }}.comentarios"
                    placeholder="Observaciones..."
                    class="input-grs text-sm"/>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-8 text-grs-texto text-sm">
        <svg class="w-10 h-10 text-grs-borde mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Sin equipos en reparación — todos operativos
    </div>
    @endforelse
</div>
