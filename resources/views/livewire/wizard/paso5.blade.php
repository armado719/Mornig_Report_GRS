{{-- ══════════════════════════════════════════════════
     PASO 5 — Cierre del Reporte
     Parámetros Perforación · Horas Tubería · Personal · Comentarios
══════════════════════════════════════════════════ --}}

{{-- ── Parámetros de Perforación (DIA vs NOCHE) ── --}}
<div class="card-grs mb-4">
    <h3 class="seccion-titulo">
        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        Parámetros de Perforación
    </h3>

    @php
    $parametros = [
        ['key' => 'peso_sub',      'label' => 'Peso Subiendo',  'unit' => 'klbf',   'step' => '0.1'],
        ['key' => 'peso_baj',      'label' => 'Peso Bajando',   'unit' => 'klbf',   'step' => '0.1'],
        ['key' => 'peso_rot',      'label' => 'Peso Rotación',  'unit' => 'klbf',   'step' => '0.1'],
        ['key' => 'presion_bomba', 'label' => 'Presión Bomba',  'unit' => 'PSI',    'step' => '1'],
        ['key' => 'rpm',           'label' => 'RPM',            'unit' => 'rpm',    'step' => '1'],
        ['key' => 'torque',        'label' => 'Torque',         'unit' => 'kft·lb', 'step' => '0.1'],
        ['key' => 'wob',           'label' => 'WOB',            'unit' => 'klbf',   'step' => '0.1'],
        ['key' => 'spm',           'label' => 'SPM',            'unit' => 'spm',    'step' => '1'],
        ['key' => 'gpm',           'label' => 'GPM',            'unit' => 'gal/m',  'step' => '1'],
        ['key' => 'rop',           'label' => 'ROP',            'unit' => 'ft/h',   'step' => '0.1'],
    ];
    @endphp

    {{-- Cabecera desktop --}}
    <div class="hidden sm:grid grid-cols-[1fr_9rem_9rem] gap-2 px-2 mb-2">
        <span class="tabla-header">Parámetro</span>
        <span class="tabla-header text-center">
            <span class="flex items-center justify-center gap-1">
                <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                </svg>
                Noche
            </span>
        </span>
        <span class="tabla-header text-center">
            <span class="flex items-center justify-center gap-1">
                <svg class="w-3 h-3 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                </svg>
                Día
            </span>
        </span>
    </div>

    <div class="space-y-1.5">
        @foreach($parametros as $p)
        <div class="grid grid-cols-1 sm:grid-cols-[1fr_9rem_9rem] gap-2 items-center
                    px-2 py-1.5 rounded-lg hover:bg-grs-fondo/30 transition-colors">

            {{-- Etiqueta --}}
            <div class="flex items-center justify-between sm:block">
                <span class="text-sm font-medium text-grs-texto">{{ $p['label'] }}</span>
                <span class="text-[10px] text-gray-400 sm:ml-1.5">{{ $p['unit'] }}</span>
            </div>

            {{-- NOCHE --}}
            <div>
                <label class="label-grs sm:hidden flex items-center gap-1">
                    <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                    Noche
                </label>
                <input type="number"
                    wire:model="par_noche_{{ $p['key'] }}"
                    step="{{ $p['step'] }}" min="0" placeholder="—"
                    inputmode="decimal"
                    class="input-grs font-mono text-center text-sm border-l-2 border-l-blue-800"/>
            </div>

            {{-- DIA --}}
            <div>
                <label class="label-grs sm:hidden flex items-center gap-1">
                    <svg class="w-3 h-3 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    Día
                </label>
                <input type="number"
                    wire:model="par_dia_{{ $p['key'] }}"
                    step="{{ $p['step'] }}" min="0" placeholder="—"
                    inputmode="decimal"
                    class="input-grs font-mono text-center text-sm border-l-2 border-l-yellow-700"/>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ── Layout 2 columnas: Horas Tubería + Personal ── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

    {{-- Horas de Rotación por Tubería --}}
    <div class="card-grs">
        <h3 class="seccion-titulo">
            <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Horas de Rotación por Tubería
        </h3>

        @php
        $tuberias = [
            ['key' => 'hr_5dp',   'label' => '5" DP',   'desc' => 'Drill Pipe NC-50'],
            ['key' => 'hr_5hwdp', 'label' => '5" HWDP', 'desc' => 'Heavy Weight NC-50'],
            ['key' => 'hr_6dc',   'label' => '6½" DC',  'desc' => 'Drill Collar NC-50'],
            ['key' => 'hr_8dc',   'label' => '8" DC',   'desc' => 'Drill Collar NC-61'],
            ['key' => 'hr_jar',   'label' => 'JAR',     'desc' => 'Activador'],
            ['key' => 'hr_monel', 'label' => 'Monel',   'desc' => 'No magnético'],
            ['key' => 'hr_otro',  'label' => 'Otro',    'desc' => 'Otro tipo'],
        ];
        $totalHrsTub = collect($tuberias)->sum(fn($t) => (float)($this->{$t['key']} ?? 0));
        @endphp

        <div class="space-y-2">
            @foreach($tuberias as $t)
            <div class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg bg-grs-fondo/40 border border-gray-600">
                <div>
                    <span class="text-sm font-mono font-bold text-grs-verde">{{ $t['label'] }}</span>
                    <span class="text-[10px] text-gray-400 ml-1.5">{{ $t['desc'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <input type="number"
                        wire:model="{{ $t['key'] }}"
                        step="0.01" min="0" placeholder="0.00"
                        inputmode="decimal"
                        class="input-grs font-mono text-right text-sm w-24"/>
                    <span class="text-xs text-gray-400 w-3">h</span>
                </div>
            </div>
            @endforeach

            @if($totalHrsTub > 0)
            <div class="pt-2 border-t border-gray-600 flex items-center justify-between px-1">
                <span class="text-xs text-grs-texto">Total</span>
                <span class="font-mono font-bold text-grs-verde text-sm">{{ number_format($totalHrsTub, 2) }} h</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Personal en Locación --}}
    <div class="card-grs">
        <h3 class="seccion-titulo">
            <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Personal en Locación
        </h3>

        <div class="space-y-3 mt-2">
            @php
            $personalItems = [
                ['key' => 'personal_grs',      'label' => 'Personal GRS',        'color' => 'text-grs-verde'],
                ['key' => 'personal_ecopetrol', 'label' => 'Ecopetrol / Operador','color' => 'text-blue-400'],
                ['key' => 'personal_flotantes', 'label' => 'Flotantes / Terceros','color' => 'text-yellow-400'],
            ];
            @endphp

            @foreach($personalItems as $pi)
            <div class="flex items-center justify-between gap-4 px-4 py-3 rounded-lg bg-grs-fondo/40 border border-gray-600">
                <label class="text-sm font-medium {{ $pi['color'] }}">{{ $pi['label'] }}</label>
                <div class="flex items-center gap-3">
                    <button type="button"
                        wire:click="$set('{{ $pi['key'] }}', max(0, {{ $this->{$pi['key']} }} - 1))"
                        class="w-7 h-7 rounded-full bg-gray-600 hover:bg-grs-acento text-white flex items-center justify-center transition-colors text-sm font-bold leading-none">−</button>
                    <span class="font-mono font-bold text-lg {{ $pi['color'] }} w-8 text-center">{{ $this->{$pi['key']} }}</span>
                    <button type="button"
                        wire:click="$set('{{ $pi['key'] }}', {{ $this->{$pi['key']} }} + 1)"
                        class="w-7 h-7 rounded-full bg-gray-600 hover:bg-grs-acento text-white flex items-center justify-center transition-colors text-sm font-bold leading-none">+</button>
                </div>
            </div>
            @endforeach

            <div class="px-4 py-2.5 rounded-lg border border-grs-verde/30 bg-grs-verde/5 flex items-center justify-between">
                <span class="text-sm font-semibold text-grs-texto">Total en locación</span>
                <span class="font-mono font-bold text-grs-verde text-lg">
                    {{ $personal_grs + $personal_ecopetrol + $personal_flotantes }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ── Comentarios Finales ── --}}
<div class="card-grs mb-4">
    <h3 class="seccion-titulo">
        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
        </svg>
        Comentarios Finales
    </h3>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div>
            <label class="label-grs flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span>
                Materiales / Faltantes
            </label>
            <textarea wire:model="com_faltantes" rows="5"
                placeholder="Lista de materiales, herramientas o insumos faltantes..."
                class="input-grs resize-none text-sm leading-relaxed"></textarea>
        </div>
        <div>
            <label class="label-grs flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-yellow-400 inline-block"></span>
                NPT — Tiempo No Productivo
            </label>
            <textarea wire:model="com_npt" rows="5"
                placeholder="Descripción de eventos de NPT, causas y acciones tomadas..."
                class="input-grs resize-none text-sm leading-relaxed"></textarea>
        </div>
        <div>
            <label class="label-grs flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-grs-verde inline-block"></span>
                Comentarios Generales
            </label>
            <textarea wire:model="com_general" rows="5"
                placeholder="Observaciones generales del turno, novedades, próximas operaciones..."
                class="input-grs resize-none text-sm leading-relaxed"></textarea>
        </div>
    </div>
</div>

{{-- ── Resumen + Acciones ── --}}
<div class="card-grs bg-grs-primario/20 border border-grs-verde/20">
    <h3 class="seccion-titulo text-grs-verde">
        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Resumen del Reporte
    </h3>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
        <div class="text-center p-3 rounded-lg bg-grs-fondo/40 border border-gray-600">
            <div class="text-xs text-gray-400 mb-1">RIG / Fecha</div>
            <div class="font-mono font-bold text-grs-verde text-sm">RIG {{ $rig }}</div>
            <div class="text-xs text-grs-texto">{{ $fecha }}</div>
        </div>
        <div class="text-center p-3 rounded-lg bg-grs-fondo/40 border border-gray-600">
            <div class="text-xs text-gray-400 mb-1">Ft. Perforados</div>
            <div class="font-mono font-bold text-grs-verde text-xl">{{ $ft_perforados ?? '—' }}</div>
            <div class="text-xs text-gray-400">ft</div>
        </div>
        <div class="text-center p-3 rounded-lg bg-grs-fondo/40 border border-gray-600">
            <div class="text-xs text-gray-400 mb-1">Actividades</div>
            <div class="font-mono font-bold text-grs-verde text-xl">
                {{ count(array_filter($operaciones, fn($op) => !empty($op['descripcion']))) }}
            </div>
            <div class="text-xs text-gray-400">registradas</div>
        </div>
        <div class="text-center p-3 rounded-lg bg-grs-fondo/40 border border-gray-600">
            <div class="text-xs text-gray-400 mb-1">Personal Total</div>
            <div class="font-mono font-bold text-grs-verde text-xl">
                {{ $personal_grs + $personal_ecopetrol + $personal_flotantes }}
            </div>
            <div class="text-xs text-gray-400">personas</div>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-600">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></div>
            <span class="text-xs text-grs-texto">
                Estado actual: <span class="font-semibold text-yellow-400">BORRADOR</span>
                — Al completar no podrá editarse
            </span>
        </div>
        @if($reporteId)
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('reportes.pdf', $reporteId) }}" target="_blank"
               class="btn-grs-outline text-xs flex items-center gap-1.5 py-1.5 px-3">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Vista previa PDF
            </a>
            <a href="{{ route('reportes.excel', $reporteId) }}"
               class="btn-grs-outline text-xs flex items-center gap-1.5 py-1.5 px-3">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar Excel
            </a>
        </div>
        @endif
    </div>
</div>
