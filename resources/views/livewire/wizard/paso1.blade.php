{{-- ══════════════════════════════════════════════════
     PASO 1 — Encabezado y Personal
══════════════════════════════════════════════════ --}}

{{-- ── SECCIÓN: Información del reporte ── --}}
<div class="card-grs mb-4">
    <h3 class="seccion-titulo">
        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Información del Reporte
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- RIG --}}
        <div>
            <label class="label-grs">RIG <span class="text-red-400">*</span></label>
            <select wire:model.live="rig"
                class="input-grs {{ $errors->has('rig') ? 'border-red-500' : '' }}"
                {{ Auth::user()->rig ? 'disabled' : '' }}>
                <option value="">— Seleccionar —</option>
                @foreach($rigs as $numero => $label)
                    <option value="{{ $numero }}">RIG {{ $label }}</option>
                @endforeach
            </select>
            @error('rig') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        {{-- Pozo --}}
        <div>
            <label class="label-grs">Pozo <span class="text-red-400">*</span></label>
            <select wire:model.live="pozo"
                class="input-grs {{ $errors->has('pozo') ? 'border-red-500' : '' }}">
                <option value="">— Seleccionar —</option>
                @foreach($pozos as $nombre => $label)
                    <option value="{{ $nombre }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('pozo') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        {{-- Fecha --}}
        <div>
            <label class="label-grs">Fecha <span class="text-red-400">*</span></label>
            <input type="date" wire:model="fecha"
                class="input-grs {{ $errors->has('fecha') ? 'border-red-500' : '' }}"/>
            @error('fecha') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        {{-- Municipio (auto) --}}
        <div>
            <label class="label-grs">Municipio</label>
            <input type="text" wire:model="municipio" placeholder="Auto desde pozo"
                class="input-grs"/>
        </div>

        {{-- Operador (auto) --}}
        <div>
            <label class="label-grs">Operador</label>
            <input type="text" wire:model="operador" placeholder="Auto desde pozo"
                class="input-grs"/>
        </div>

        {{-- Días desde Spud --}}
        <div>
            <label class="label-grs">Días desde Spud</label>
            <input type="number" wire:model="dias_spud"
                step="0.5" min="0" placeholder="0.0"
                inputmode="decimal"
                class="input-grs font-mono"/>
        </div>

        {{-- Operación Actual --}}
        <div class="sm:col-span-2 lg:col-span-3">
            <label class="label-grs">Operación Actual</label>
            <input type="text" wire:model="operacion_actual"
                placeholder="Ej: Perforando 8½" a 8,540 ft"
                class="input-grs"/>
        </div>

    </div>
</div>

{{-- ── SECCIÓN: Profundidades ── --}}
<div class="card-grs mb-4">
    <h3 class="seccion-titulo">
        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
        </svg>
        Profundidades (ft)
    </h3>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Prof. Programada --}}
        <div>
            <label class="label-grs">Prof. Programada</label>
            <div class="relative">
                <input type="number" wire:model="prof_programada_ft"
                    step="0.01" min="0" placeholder="0.00"
                    inputmode="decimal"
                    class="input-grs font-mono pr-8"/>
                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-600">ft</span>
            </div>
        </div>

        {{-- Prof. Ayer --}}
        <div>
            <label class="label-grs">Prof. Ayer</label>
            <div class="relative">
                <input type="number" wire:model.live="prof_ayer_ft"
                    step="0.01" min="0" placeholder="0.00"
                    inputmode="decimal"
                    class="input-grs font-mono pr-8"/>
                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-600">ft</span>
            </div>
        </div>

        {{-- Prof. Hoy --}}
        <div>
            <label class="label-grs">Prof. Hoy</label>
            <div class="relative">
                <input type="number" wire:model.live="prof_hoy_ft"
                    step="0.01" min="0" placeholder="0.00"
                    inputmode="decimal"
                    class="input-grs font-mono pr-8"/>
                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-600">ft</span>
            </div>
        </div>

        {{-- Ft Perforados (calculado) --}}
        <div>
            <label class="label-grs">Ft Perforados
                <span class="text-[9px] text-grs-verde normal-case">(auto)</span>
            </label>
            <div class="relative">
                <input type="number" wire:model="ft_perforados"
                    step="0.01" placeholder="—"
                    inputmode="decimal"
                    class="input-grs font-mono pr-8 {{ $ft_perforados !== null && $ft_perforados < 0 ? 'border-red-500' : '' }}"
                    readonly/>
                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-600">ft</span>
            </div>
            @if($ft_perforados !== null && $ft_perforados < 0)
                <p class="mt-1 text-xs text-red-400">Prof. Hoy menor que Prof. Ayer</p>
            @endif
        </div>

    </div>
</div>

{{-- ── SECCIÓN: Horas y Preventoras ── --}}
<div class="card-grs mb-4">
    <h3 class="seccion-titulo">
        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Horas de Rotación & Preventoras
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Hrs Rotación --}}
        <div>
            <label class="label-grs">Hrs Rotación (día)</label>
            <div class="relative">
                <input type="number" wire:model="hrs_rotacion"
                    step="0.01" min="0" max="24" placeholder="0.00"
                    inputmode="decimal"
                    class="input-grs font-mono pr-8 {{ $errors->has('hrs_rotacion') ? 'border-red-500' : '' }}"/>
                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-600">h</span>
            </div>
            @error('hrs_rotacion') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        {{-- Horas Acum. Rotación --}}
        <div>
            <label class="label-grs">Hrs Acum. Rotación</label>
            <div class="relative">
                <input type="number" wire:model="horas_acum_rotacion"
                    step="0.01" min="0" placeholder="0.00"
                    inputmode="decimal"
                    class="input-grs font-mono pr-8"/>
                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-600">h</span>
            </div>
        </div>

        {{-- Fecha Prueba Preventoras --}}
        <div>
            <label class="label-grs">Fecha Prueba Preventoras</label>
            <input type="date" wire:model="prueba_preventoras_fecha"
                class="input-grs"/>
        </div>

        {{-- Comentarios preventoras --}}
        <div class="sm:col-span-2 lg:col-span-4">
            <label class="label-grs">Comentarios Preventoras</label>
            <textarea wire:model="prueba_preventoras_comentarios"
                rows="2"
                placeholder="Observaciones sobre la prueba de preventoras..."
                class="input-grs resize-none"></textarea>
        </div>

    </div>
</div>

{{-- ── SECCIÓN: Personal en turno ── --}}
<div class="card-grs">
    <h3 class="seccion-titulo">
        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        Personal en Turno
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">

        <div>
            <label class="label-grs">Rig Manager</label>
            <input type="text" wire:model="p_rig_manager"
                placeholder="Nombre completo"
                class="input-grs"/>
        </div>

        <div>
            <label class="label-grs">DSM</label>
            <input type="text" wire:model="p_dsm"
                placeholder="Nombre completo"
                class="input-grs"/>
        </div>

        <div>
            <label class="label-grs">Supervisor de Pozo</label>
            <input type="text" wire:model="p_supervisor"
                placeholder="Nombre completo"
                class="input-grs"/>
        </div>

        <div>
            <label class="label-grs">HSEQ</label>
            <input type="text" wire:model="p_hseq"
                placeholder="Nombre completo"
                class="input-grs"/>
        </div>

    </div>

    {{-- Indicadores HSEQ ── --}}
    <div class="border-t border-grs-borde pt-4">
        <p class="text-xs font-bold uppercase tracking-widest text-grs-verde mb-3">Indicadores HSEQ</p>
        <div class="flex flex-wrap gap-6">

            {{-- Días sin LTI --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-900 border border-green-700 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <label class="label-grs mb-0">Días sin LTI</label>
                    <input type="number" wire:model="p_dias_sin_lti"
                        min="0" inputmode="numeric"
                        class="input-grs w-24 font-mono font-bold text-center text-green-700"/>
                </div>
            </div>

            {{-- Días sin RWC --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-blue-900 border border-blue-700 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <label class="label-grs mb-0">Días sin RWC</label>
                    <input type="number" wire:model="p_dias_sin_rwc"
                        min="0" inputmode="numeric"
                        class="input-grs w-24 font-mono font-bold text-center text-blue-700"/>
                </div>
            </div>

        </div>
    </div>
</div>
