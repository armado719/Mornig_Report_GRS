<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte Diario FGPO-002 — RIG {{ $reporte->rig }} — {{ $reporte->fecha?->format('d/m/Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9px; color: #1a1a1a; background: #fff; }
        .page { padding: 15px; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; padding-bottom: 8px; border-bottom: 2px solid #1B4D35; }
        .logo-area { }
        .logo-title { font-size: 14px; font-weight: bold; color: #1B4D35; }
        .logo-sub { font-size: 9px; color: #555; }
        .report-id { text-align: right; }
        .report-id .cod { font-size: 9px; color: #888; }
        .report-id .rig { font-size: 16px; font-weight: bold; color: #1B4D35; }
        .report-id .fecha { font-size: 10px; color: #333; }

        /* Estado badge */
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 7px; font-weight: bold; text-transform: uppercase; }
        .badge-completado { background: #d1fae5; color: #065f46; }
        .badge-borrador { background: #fef3c7; color: #92400e; }

        /* Sección */
        .section { margin-bottom: 10px; }
        .section-title { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #1B4D35; border-bottom: 1px solid #d1fae5; padding-bottom: 3px; margin-bottom: 6px; letter-spacing: 0.5px; }

        /* Grid de campos */
        .field-grid { display: flex; flex-wrap: wrap; gap: 4px; }
        .field { flex: 1; min-width: 70px; }
        .field-label { font-size: 7px; color: #888; text-transform: uppercase; }
        .field-value { font-size: 9px; color: #111; font-weight: 500; }
        .field-value.mono { font-family: Courier New, monospace; }
        .field-value.green { color: #1B4D35; font-weight: bold; }

        /* Tabla */
        table { width: 100%; border-collapse: collapse; font-size: 8px; }
        th { background: #1B4D35; color: #fff; padding: 4px 5px; text-align: left; font-size: 7px; text-transform: uppercase; }
        th.center, td.center { text-align: center; }
        td { padding: 3px 5px; border-bottom: 1px solid #e5e7eb; color: #333; }
        tr.noche { background: #eff6ff; }
        tr.dia { background: #f9fafb; }
        tr:last-child td { border-bottom: none; }

        /* 2 col layout */
        .two-col { display: flex; gap: 10px; }
        .two-col > div { flex: 1; }

        /* Kpi row */
        .kpi-row { display: flex; gap: 6px; margin-bottom: 10px; }
        .kpi { flex: 1; text-align: center; padding: 6px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 4px; }
        .kpi-value { font-size: 14px; font-weight: bold; color: #1B4D35; font-family: Courier New, monospace; }
        .kpi-label { font-size: 7px; color: #6b7280; text-transform: uppercase; }

        .textarea-content { font-size: 8px; color: #333; white-space: pre-wrap; background: #f9fafb; padding: 5px; border: 1px solid #e5e7eb; border-radius: 3px; min-height: 30px; }

        .footer { margin-top: 15px; padding-top: 8px; border-top: 1px solid #d1fae5; display: flex; justify-content: space-between; font-size: 7px; color: #888; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <div class="logo-area">
            <div class="logo-title">General Rigs Services S.A.S.</div>
            <div class="logo-sub">Reporte Diario de Perforación · FGPO-002</div>
            @if($reporte->estado === 'COMPLETADO')
                <span class="badge badge-completado">Completado</span>
            @else
                <span class="badge badge-borrador">Borrador</span>
            @endif
        </div>
        <div class="report-id">
            <div class="cod">RIG</div>
            <div class="rig">{{ $reporte->rig }}</div>
            <div class="fecha">{{ $reporte->fecha?->format('d/m/Y') }}</div>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="kpi-row">
        <div class="kpi">
            <div class="kpi-value">{{ $reporte->ft_perforados ? number_format($reporte->ft_perforados, 0) : '—' }}</div>
            <div class="kpi-label">Ft. Perforados</div>
        </div>
        <div class="kpi">
            <div class="kpi-value">{{ $reporte->prof_hoy_ft ? number_format($reporte->prof_hoy_ft, 0) : '—' }}</div>
            <div class="kpi-label">Prof. Hoy (ft)</div>
        </div>
        <div class="kpi">
            <div class="kpi-value">{{ $reporte->hrs_rotacion ? number_format($reporte->hrs_rotacion, 1) : '—' }}</div>
            <div class="kpi-label">Hrs Rotación</div>
        </div>
        <div class="kpi">
            <div class="kpi-value">{{ ($reporte->personal_grs + $reporte->personal_ecopetrol + $reporte->personal_flotantes) ?: '—' }}</div>
            <div class="kpi-label">Personal Total</div>
        </div>
        <div class="kpi">
            <div class="kpi-value">{{ $reporte->dias_spud ?: '—' }}</div>
            <div class="kpi-label">Días SPUD</div>
        </div>
    </div>

    {{-- Encabezado --}}
    <div class="section">
        <div class="section-title">Información del Reporte</div>
        <div class="field-grid">
            <div class="field"><div class="field-label">Pozo</div><div class="field-value">{{ $reporte->pozo }}</div></div>
            <div class="field"><div class="field-label">Municipio</div><div class="field-value">{{ $reporte->municipio }}</div></div>
            <div class="field"><div class="field-label">Operador</div><div class="field-value">{{ $reporte->operador }}</div></div>
            <div class="field"><div class="field-label">Prof. Programada</div><div class="field-value mono green">{{ $reporte->prof_programada_ft ? number_format($reporte->prof_programada_ft, 0).' ft' : '—' }}</div></div>
            <div class="field"><div class="field-label">Prof. Ayer</div><div class="field-value mono">{{ $reporte->prof_ayer_ft ? number_format($reporte->prof_ayer_ft, 0).' ft' : '—' }}</div></div>
            <div class="field"><div class="field-label">Operación Actual</div><div class="field-value">{{ $reporte->operacion_actual ?: '—' }}</div></div>
        </div>
        @if($reporte->personal)
        <div class="field-grid" style="margin-top: 5px;">
            <div class="field"><div class="field-label">Rig Manager</div><div class="field-value">{{ $reporte->personal->rig_manager ?: '—' }}</div></div>
            <div class="field"><div class="field-label">DSM</div><div class="field-value">{{ $reporte->personal->dsm ?: '—' }}</div></div>
            <div class="field"><div class="field-label">Supervisor</div><div class="field-value">{{ $reporte->personal->supervisor ?: '—' }}</div></div>
            <div class="field"><div class="field-label">HSEQ</div><div class="field-value">{{ $reporte->personal->hseq ?: '—' }}</div></div>
            <div class="field"><div class="field-label">Días sin LTI</div><div class="field-value mono green">{{ $reporte->personal->dias_sin_lti }}</div></div>
            <div class="field"><div class="field-label">Días sin RWC</div><div class="field-value mono green">{{ $reporte->personal->dias_sin_rwc }}</div></div>
        </div>
        @endif
    </div>

    {{-- Cronología --}}
    @if($reporte->operaciones->isNotEmpty())
    <div class="section">
        <div class="section-title">Cronología de Operaciones</div>
        <table>
            <thead>
                <tr>
                    <th>Desde</th><th>Hasta</th><th class="center">Horas</th>
                    <th class="center">Cód.</th><th>Descripción</th>
                    <th class="center">Turno</th><th class="center">Noche h</th><th class="center">Día h</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reporte->operaciones as $op)
                <tr class="{{ $op->turno === 'NOCHE' ? 'noche' : 'dia' }}">
                    <td class="mono">{{ $op->hora_desde }}</td>
                    <td class="mono">{{ $op->hora_hasta }}</td>
                    <td class="center mono">{{ $op->horas ? number_format($op->horas, 1) : '—' }}</td>
                    <td class="center">{{ $op->codigo ?: '—' }}</td>
                    <td>{{ $op->descripcion }}</td>
                    <td class="center">{{ $op->turno }}</td>
                    <td class="center mono">{{ $op->noche_hrs ? number_format($op->noche_hrs, 1) : '0' }}</td>
                    <td class="center mono">{{ $op->dia_hrs ? number_format($op->dia_hrs, 1) : '0' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Dos columnas: Lodo + Diesel --}}
    <div class="two-col">
        {{-- Lodo --}}
        @if($reporte->lodo)
        <div class="section">
            <div class="section-title">Lodo</div>
            <div class="field-grid">
                <div class="field" style="min-width: 120px;"><div class="field-label">Tipo</div><div class="field-value">{{ $reporte->lodo->tipo ?: '—' }}</div></div>
                <div class="field"><div class="field-label">Peso (ppg)</div><div class="field-value mono">{{ $reporte->lodo->peso ?: '—' }}</div></div>
                <div class="field"><div class="field-label">PV (cP)</div><div class="field-value mono">{{ $reporte->lodo->pv ?: '—' }}</div></div>
                <div class="field"><div class="field-label">YP</div><div class="field-value mono">{{ $reporte->lodo->yp ?: '—' }}</div></div>
                <div class="field"><div class="field-label">pH</div><div class="field-value mono">{{ $reporte->lodo->ph ?: '—' }}</div></div>
                <div class="field"><div class="field-label">Viscosidad</div><div class="field-value mono">{{ $reporte->lodo->viscosidad ?: '—' }}</div></div>
                <div class="field"><div class="field-label">Geles</div><div class="field-value mono">{{ $reporte->lodo->geles ?: '—' }}</div></div>
                <div class="field"><div class="field-label">Sólidos%</div><div class="field-value mono">{{ $reporte->lodo->solidos ?: '—' }}</div></div>
            </div>
        </div>
        @endif

        {{-- Diesel --}}
        @if($reporte->diesel)
        <div class="section">
            <div class="section-title">Diesel (galones)</div>
            <table>
                <tr><td>Inv. Ayer</td><td class="center mono">{{ $reporte->diesel->ayer ? number_format($reporte->diesel->ayer, 0) : '—' }}</td></tr>
                <tr><td>Recibido</td><td class="center mono">{{ $reporte->diesel->recibido ? number_format($reporte->diesel->recibido, 0) : '—' }}</td></tr>
                <tr><td>Inv. Hoy</td><td class="center mono">{{ $reporte->diesel->hoy ? number_format($reporte->diesel->hoy, 0) : '—' }}</td></tr>
                <tr><td>Usado</td><td class="center mono">{{ $reporte->diesel->usado ? number_format($reporte->diesel->usado, 0) : '—' }}</td></tr>
                <tr><td>Acumulado</td><td class="center mono">{{ $reporte->diesel->acumulado ? number_format($reporte->diesel->acumulado, 0) : '—' }}</td></tr>
            </table>
        </div>
        @endif
    </div>

    {{-- BHA + Top Drive --}}
    <div class="two-col">
        @if($reporte->bhaBroca)
        <div class="section">
            <div class="section-title">BHA + Broca</div>
            <div class="field-grid">
                <div class="field"><div class="field-label">No. Broca</div><div class="field-value mono">{{ $reporte->bhaBroca->numero ?: '—' }}</div></div>
                <div class="field"><div class="field-label">Tamaño</div><div class="field-value mono">{{ $reporte->bhaBroca->tamano ?: '—' }}</div></div>
                <div class="field"><div class="field-label">Tipo</div><div class="field-value">{{ $reporte->bhaBroca->tipo ?: '—' }}</div></div>
                <div class="field"><div class="field-label">Jets</div><div class="field-value mono">{{ $reporte->bhaBroca->jets ?: '—' }}</div></div>
                <div class="field"><div class="field-label">Total BHA (ft)</div><div class="field-value mono green">{{ $reporte->bhaBroca->total_bha ?: '—' }}</div></div>
            </div>
        </div>
        @endif

        @if($reporte->topDrive)
        <div class="section">
            <div class="section-title">Top Drive</div>
            <table>
                <tr><td>Horas Rotación</td><td class="center mono">{{ $reporte->topDrive->hrs_rotacion ?: '—' }} h</td></tr>
                <tr><td>Horas Unidad</td><td class="center mono">{{ $reporte->topDrive->hrs_unidad ?: '—' }} h</td></tr>
                <tr><td>Horas Motor</td><td class="center mono">{{ $reporte->topDrive->hrs_motor ?: '—' }} h</td></tr>
                <tr><td>Acum. Rotación</td><td class="center mono">{{ $reporte->topDrive->acum_rotacion ?: '—' }} h</td></tr>
            </table>
        </div>
        @endif
    </div>

    {{-- Parámetros de perforación --}}
    @if($reporte->parametros->isNotEmpty())
    <div class="section">
        <div class="section-title">Parámetros de Perforación</div>
        @php
        $pD = $reporte->parametros->firstWhere('turno', 'DIA');
        $pN = $reporte->parametros->firstWhere('turno', 'NOCHE');
        @endphp
        <table>
            <thead>
                <tr>
                    <th>Parámetro</th>
                    <th class="center">🌙 Noche</th>
                    <th class="center">☀ Día</th>
                </tr>
            </thead>
            <tbody>
                @foreach([
                    ['Peso Subiendo (klbf)', 'peso_subiendo'],
                    ['Peso Bajando (klbf)', 'peso_bajando'],
                    ['Peso Rotación (klbf)', 'peso_rotacion'],
                    ['Presión Bomba (PSI)', 'presion_bomba_psi'],
                    ['RPM', 'rpm'],
                    ['Torque (kft·lb)', 'torque'],
                    ['WOB (klbf)', 'wob'],
                    ['SPM', 'spm'],
                    ['GPM', 'gal/m', 'gpm'],
                    ['ROP (ft/h)', 'rop'],
                ] as $row)
                @php $field = $row[2] ?? $row[1]; @endphp
                <tr>
                    <td>{{ $row[0] }}</td>
                    <td class="center mono">{{ $pN?->$field ?? '—' }}</td>
                    <td class="center mono">{{ $pD?->$field ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- Comentarios --}}
    @php $cm = $reporte->comentarios->keyBy('tipo'); @endphp
    @if($cm->count() > 0)
    <div class="section">
        <div class="section-title">Comentarios Finales</div>
        <div class="two-col">
            @foreach(['FALTANTES' => 'Materiales / Faltantes', 'NPT' => 'NPT', 'GENERAL' => 'General'] as $tipo => $titulo)
            @if($cm->has($tipo) && $cm[$tipo]->contenido)
            <div>
                <div class="field-label" style="margin-bottom: 3px;">{{ $titulo }}</div>
                <div class="textarea-content">{{ $cm[$tipo]->contenido }}</div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif

    <div class="footer">
        <span>GRS RIG {{ $reporte->rig }} — {{ $reporte->pozo }} — {{ $reporte->fecha?->format('d/m/Y') }}</span>
        <span>FGPO-002 | Generado: {{ now()->format('d/m/Y H:i') }}</span>
    </div>
</div>
</body>
</html>
