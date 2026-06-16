<x-app-layout>
    <x-slot name="titulo">Reporte — RIG {{ $reporte->rig }} — {{ $reporte->fecha?->format('d/m/Y') }}</x-slot>

    {{-- Barra de acciones --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('reportes.index') }}" class="btn-grs-outline text-xs flex items-center gap-1.5 py-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>
            @if($reporte->estado === 'COMPLETADO')
                <span class="badge-completado">Completado</span>
            @else
                <span class="badge-borrador">Borrador</span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            @if($reporte->estado === 'BORRADOR')
            <a href="{{ route('reportes.editar', $reporte->id) }}" class="btn-grs-outline text-xs flex items-center gap-1.5 py-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
            @endif
            <a href="{{ route('reportes.pdf', $reporte->id) }}" target="_blank" class="btn-grs-outline text-xs flex items-center gap-1.5 py-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar PDF
            </a>
            <a href="{{ route('reportes.excel', $reporte->id) }}" class="btn-grs flex items-center gap-1.5 text-xs py-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar Excel
            </a>
        </div>
    </div>

    {{-- ── ENCABEZADO ── --}}
    <div class="card-grs mb-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
                <div class="tabla-header">RIG</div>
                <div class="font-mono font-bold text-grs-verde text-lg">{{ $reporte->rig }}</div>
            </div>
            <div>
                <div class="tabla-header">Fecha</div>
                <div class="font-mono text-white">{{ $reporte->fecha?->format('d/m/Y') }}</div>
            </div>
            <div class="col-span-2">
                <div class="tabla-header">Pozo / Municipio</div>
                <div class="text-white font-medium">{{ $reporte->pozo }}</div>
                <div class="text-grs-texto text-xs">{{ $reporte->municipio }} — {{ $reporte->operador }}</div>
            </div>
        </div>

        @if($reporte->personal)
        <div class="mt-4 pt-4 border-t border-gray-600 grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach([['Rig Manager', $reporte->personal->rig_manager], ['DSM', $reporte->personal->dsm], ['Supervisor', $reporte->personal->supervisor], ['HSEQ', $reporte->personal->hseq]] as [$titulo, $valor])
            <div>
                <div class="tabla-header">{{ $titulo }}</div>
                <div class="text-white text-sm">{{ $valor ?: '—' }}</div>
            </div>
            @endforeach
        </div>
        <div class="mt-3 flex gap-6">
            <div>
                <span class="tabla-header">Días sin LTI</span>
                <span class="font-mono font-bold text-grs-verde ml-2">{{ $reporte->personal->dias_sin_lti }}</span>
            </div>
            <div>
                <span class="tabla-header">Días sin RWC</span>
                <span class="font-mono font-bold text-grs-verde ml-2">{{ $reporte->personal->dias_sin_rwc }}</span>
            </div>
        </div>
        @endif
    </div>

    {{-- ── PROFUNDIDADES ── --}}
    <div class="card-grs mb-4">
        <h3 class="seccion-titulo">Profundidades y Operación</h3>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            @foreach([
                ['Prof. Programada', $reporte->prof_programada_ft, 'ft'],
                ['Prof. Ayer', $reporte->prof_ayer_ft, 'ft'],
                ['Prof. Hoy', $reporte->prof_hoy_ft, 'ft'],
                ['Ft. Perforados', $reporte->ft_perforados, 'ft'],
                ['Horas Rotación', $reporte->hrs_rotacion, 'h'],
            ] as [$lbl, $val, $unit])
            <div class="text-center p-3 rounded-lg bg-grs-fondo/40 border border-gray-600">
                <div class="text-[10px] text-gray-400 mb-1">{{ $lbl }}</div>
                <div class="font-mono font-bold text-grs-verde text-sm">{{ $val ? number_format($val, 0) : '—' }}</div>
                <div class="text-[10px] text-gray-400">{{ $unit }}</div>
            </div>
            @endforeach
        </div>
        @if($reporte->operacion_actual)
        <div class="mt-3 px-3 py-2 rounded-lg bg-grs-fondo/40 border border-gray-600">
            <span class="tabla-header">Operación actual:</span>
            <span class="text-white text-sm ml-2">{{ $reporte->operacion_actual }}</span>
        </div>
        @endif
    </div>

    {{-- ── CRONOLOGÍA ── --}}
    @if($reporte->operaciones->isNotEmpty())
    <div class="card-grs mb-4">
        <h3 class="seccion-titulo">Cronología de Operaciones</h3>
        <div class="hidden sm:grid grid-cols-[5rem_5rem_4rem_4rem_3rem_1fr_5rem] gap-2 px-2 mb-1">
            <span class="tabla-header">Desde</span>
            <span class="tabla-header">Hasta</span>
            <span class="tabla-header text-center">Horas</span>
            <span class="tabla-header text-center">Código</span>
            <span class="tabla-header text-center">Turno</span>
            <span class="tabla-header">Descripción</span>
            <span class="tabla-header text-center">N / D</span>
        </div>
        <div class="space-y-1">
            @foreach($reporte->operaciones as $op)
            <div class="grid grid-cols-1 sm:grid-cols-[5rem_5rem_4rem_4rem_3rem_1fr_5rem] gap-2 items-center
                        px-2 py-1.5 rounded-lg
                        {{ $op->turno === 'NOCHE' ? 'bg-blue-950/30' : 'bg-grs-fondo/20' }}">
                <span class="font-mono text-grs-texto text-xs">{{ $op->hora_desde }}</span>
                <span class="font-mono text-grs-texto text-xs">{{ $op->hora_hasta }}</span>
                <span class="font-mono text-grs-verde text-xs text-center font-bold">{{ $op->horas ? number_format($op->horas, 1).'h' : '—' }}</span>
                <span class="font-mono text-xs text-center text-yellow-400">{{ $op->codigo ?: '—' }}</span>
                <span class="text-[10px] text-center {{ $op->turno === 'NOCHE' ? 'text-blue-400' : 'text-yellow-300' }}">{{ $op->turno }}</span>
                <span class="text-grs-texto text-xs">{{ $op->descripcion }}</span>
                <span class="font-mono text-[10px] text-center text-grs-texto">
                    {{ $op->noche_hrs ? number_format($op->noche_hrs,1) : '0' }}h /
                    {{ $op->dia_hrs ? number_format($op->dia_hrs,1) : '0' }}h
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── LODO + BOMBAS ── --}}
    @if($reporte->lodo)
    <div class="card-grs mb-4">
        <h3 class="seccion-titulo">Información del Lodo</h3>
        <div class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-9 gap-2 text-center">
            @foreach([
                ['Tipo', $reporte->lodo->tipo ?? '—', ''],
                ['Peso', $reporte->lodo->peso, 'ppg'],
                ['PV', $reporte->lodo->pv, 'cP'],
                ['YP', $reporte->lodo->yp, 'lb/100ft²'],
                ['pH', $reporte->lodo->ph, ''],
                ['Torta', $reporte->lodo->torta, '32nds'],
                ['Viscosidad', $reporte->lodo->viscosidad, 'seg/qt'],
                ['Geles', $reporte->lodo->geles, ''],
                ['Sólidos', $reporte->lodo->solidos, '%'],
            ] as [$lbl, $val, $unit])
            <div class="p-2 rounded bg-grs-fondo/40 border border-gray-600">
                <div class="text-[9px] text-gray-400 mb-0.5">{{ $lbl }}</div>
                <div class="font-mono text-grs-verde text-xs font-bold">{{ $val ?? '—' }}{{ $val && $unit ? ' '.$unit : '' }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── DIESEL ── --}}
    @if($reporte->diesel)
    <div class="card-grs mb-4">
        <h3 class="seccion-titulo">Diesel</h3>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            @foreach([
                ['Ayer', $reporte->diesel->ayer],
                ['Recibido', $reporte->diesel->recibido],
                ['Hoy', $reporte->diesel->hoy],
                ['Usado', $reporte->diesel->usado],
                ['Acumulado', $reporte->diesel->acumulado],
            ] as [$lbl, $val])
            <div class="text-center p-2 rounded-lg bg-grs-fondo/40 border border-gray-600">
                <div class="text-[10px] text-gray-400 mb-1">{{ $lbl }}</div>
                <div class="font-mono font-bold text-grs-verde text-sm">{{ $val ? number_format($val, 0) : '—' }}</div>
                <div class="text-[10px] text-gray-400">gal</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── BHA + TOP DRIVE ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        @if($reporte->bhaBroca)
        <div class="card-grs">
            <h3 class="seccion-titulo">BHA + Broca</h3>
            <div class="grid grid-cols-2 gap-2 text-sm">
                @foreach([
                    ['No. Broca', $reporte->bhaBroca->numero],
                    ['Tamaño', $reporte->bhaBroca->tamano],
                    ['Tipo', $reporte->bhaBroca->tipo],
                    ['Jets', $reporte->bhaBroca->jets],
                    ['Serie', $reporte->bhaBroca->serie],
                    ['Total BHA', $reporte->bhaBroca->total_bha ? $reporte->bhaBroca->total_bha.' ft' : null],
                ] as [$lbl, $val])
                <div>
                    <div class="tabla-header">{{ $lbl }}</div>
                    <div class="font-mono text-white text-sm">{{ $val ?: '—' }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($reporte->topDrive)
        <div class="card-grs">
            <h3 class="seccion-titulo">Top Drive</h3>
            <div class="space-y-2">
                @foreach([
                    ['Horas Rotación', $reporte->topDrive->hrs_rotacion, 'h'],
                    ['Horas Unidad', $reporte->topDrive->hrs_unidad, 'h'],
                    ['Horas Motor', $reporte->topDrive->hrs_motor, 'h'],
                    ['Acum. Rotación', $reporte->topDrive->acum_rotacion, 'h'],
                ] as [$lbl, $val, $unit])
                <div class="flex justify-between px-3 py-2 rounded-lg bg-grs-fondo/40 border border-gray-600">
                    <span class="text-sm text-grs-texto">{{ $lbl }}</span>
                    <span class="font-mono font-bold text-grs-verde text-sm">{{ $val ? $val.' '.$unit : '—' }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- ── PARÁMETROS PERFORACIÓN ── --}}
    @if($reporte->parametros->isNotEmpty())
    <div class="card-grs mb-4">
        <h3 class="seccion-titulo">Parámetros de Perforación</h3>
        @php
        $pDia   = $reporte->parametros->firstWhere('turno', 'DIA');
        $pNoche = $reporte->parametros->firstWhere('turno', 'NOCHE');
        $paramRows = [
            ['Peso Subiendo (klbf)',  'peso_subiendo'],
            ['Peso Bajando (klbf)',   'peso_bajando'],
            ['Peso Rotación (klbf)',  'peso_rotacion'],
            ['Presión Bomba (PSI)',   'presion_bomba_psi'],
            ['RPM',                  'rpm'],
            ['Torque (kft·lb)',       'torque'],
            ['WOB (klbf)',           'wob'],
            ['SPM',                  'spm'],
            ['GPM',                  'gpm'],
            ['ROP (ft/h)',           'rop'],
        ];
        @endphp
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-grs-borde">
                        <th class="tabla-header text-left py-2 pr-4">Parámetro</th>
                        <th class="tabla-header text-center py-2 px-4">
                            <span class="flex items-center justify-center gap-1">
                                <svg class="w-3 h-3 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                                Noche
                            </span>
                        </th>
                        <th class="tabla-header text-center py-2 px-4">
                            <span class="flex items-center justify-center gap-1">
                                <svg class="w-3 h-3 text-yellow-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/></svg>
                                Día
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-grs-borde/40">
                    @foreach($paramRows as [$label, $field])
                    <tr class="hover:bg-grs-fondo/30">
                        <td class="py-1.5 pr-4 text-grs-texto">{{ $label }}</td>
                        <td class="py-1.5 px-4 text-center font-mono text-grs-verde">{{ $pNoche?->$field ?? '—' }}</td>
                        <td class="py-1.5 px-4 text-center font-mono text-grs-verde">{{ $pDia?->$field ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ── COMENTARIOS ── --}}
    @php
    $comentariosMap = $reporte->comentarios->keyBy('tipo');
    @endphp
    @if($comentariosMap->count() > 0)
    <div class="card-grs mb-4">
        <h3 class="seccion-titulo">Comentarios Finales</h3>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            @foreach(['FALTANTES' => 'Materiales / Faltantes', 'NPT' => 'NPT — Tiempo No Productivo', 'GENERAL' => 'Comentarios Generales'] as $tipo => $titulo)
            @if($comentariosMap->has($tipo) && $comentariosMap[$tipo]->contenido)
            <div>
                <div class="label-grs">{{ $titulo }}</div>
                <div class="text-grs-texto text-sm whitespace-pre-line px-3 py-2 rounded-lg bg-grs-fondo/40 border border-gray-600">
                    {{ $comentariosMap[$tipo]->contenido }}
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

</x-app-layout>
