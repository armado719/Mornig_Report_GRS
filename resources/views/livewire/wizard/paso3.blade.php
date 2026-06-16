{{-- ══════════════════════════════════════════════════
     PASO 3 — Datos Técnicos
     Lodo · Bombas · Cable de Perforación · Diesel
══════════════════════════════════════════════════ --}}

{{-- ── SECCIÓN 1: Información del Lodo ── --}}
<div class="card-grs mb-4">
    <h3 class="seccion-titulo">
        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
        </svg>
        Información del Lodo
    </h3>

    {{-- Fila 1: Tipo, Viscosidad, Peso, Geles --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-3">
        <div class="sm:col-span-2">
            <label class="label-grs">Tipo de Lodo</label>
            <input type="text" wire:model="l_tipo"
                placeholder="Ej: KCL/Polímero, WBM, OBM..."
                class="input-grs"/>
        </div>
        <div>
            <label class="label-grs">Viscosidad</label>
            <input type="text" wire:model="l_viscosidad"
                placeholder="Ej: 45 seg/qt"
                class="input-grs font-mono"/>
        </div>
        <div>
            <label class="label-grs">Geles (10s/10m/30m)</label>
            <input type="text" wire:model="l_geles"
                placeholder="Ej: 9/17/31"
                class="input-grs font-mono"/>
        </div>
    </div>

    {{-- Fila 2: Propiedades numéricas --}}
    <div class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-9 gap-3">

        @php
        $camposLodo = [
            ['key' => 'l_peso',    'label' => 'Peso',     'unit' => 'ppg',  'step' => '0.1', 'ph' => '10.0'],
            ['key' => 'l_pv',      'label' => 'PV',       'unit' => 'cP',   'step' => '1',   'ph' => '0'],
            ['key' => 'l_yp',      'label' => 'YP',       'unit' => 'lb/100ft²','step' => '1','ph' => '0'],
            ['key' => 'l_torta',   'label' => 'Torta',    'unit' => '32nds','step' => '0.5', 'ph' => '0.0'],
            ['key' => 'l_ph',      'label' => 'pH',       'unit' => '',     'step' => '0.1', 'ph' => '0.0'],
            ['key' => 'l_cloruros','label' => 'Cloruros', 'unit' => 'mg/L', 'step' => '100', 'ph' => '0'],
            ['key' => 'l_oil_pct', 'label' => 'Oil %',   'unit' => '%',    'step' => '0.1', 'ph' => '0.0'],
            ['key' => 'l_flu_loss','label' => 'Flu Loss', 'unit' => 'cc',   'step' => '0.1', 'ph' => '0.0'],
            ['key' => 'l_solidos', 'label' => 'Sólidos',  'unit' => '%',    'step' => '0.1', 'ph' => '0.0'],
        ];
        @endphp

        @foreach($camposLodo as $c)
        <div>
            <label class="label-grs">{{ $c['label'] }}
                @if($c['unit'])<span class="text-[9px] normal-case text-gray-600">{{ $c['unit'] }}</span>@endif
            </label>
            <input type="number"
                wire:model="{{ $c['key'] }}"
                step="{{ $c['step'] }}"
                min="0"
                placeholder="{{ $c['ph'] }}"
                inputmode="decimal"
                class="input-grs font-mono text-center text-sm"/>
        </div>
        @endforeach

    </div>

    {{-- Arena por separado --}}
    <div class="mt-3 max-w-xs">
        <label class="label-grs">Arena <span class="text-[9px] normal-case text-gray-600">%</span></label>
        <input type="number" wire:model="l_arena"
            step="0.01" min="0" placeholder="0.00"
            inputmode="decimal"
            class="input-grs font-mono text-center text-sm"/>
    </div>
</div>

{{-- ── SECCIÓN 2: Bombas de Lodo (3 fijas) ── --}}
<div class="card-grs mb-4">
    <h3 class="seccion-titulo">
        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
        </svg>
        Bombas de Lodo
    </h3>

    {{-- Cabecera tabla --}}
    <div class="hidden sm:grid grid-cols-[8rem_8rem_1fr_6rem_5rem_7rem] gap-2 mb-1 px-2">
        <span class="tabla-header">Número / ID</span>
        <span class="tabla-header">Camisa / Diám.</span>
        <span class="tabla-header text-center">Profundidad (ft)</span>
        <span class="tabla-header text-center">Peso Lodo (ppg)</span>
        <span class="tabla-header text-center">SPM</span>
        <span class="tabla-header text-center">Presión (PSI)</span>
    </div>

    <div class="space-y-2">
        @foreach($bombas as $i => $bomba)
        <div class="grid grid-cols-1 sm:grid-cols-[8rem_8rem_1fr_6rem_5rem_7rem] gap-2 items-center
                    p-2 rounded-lg bg-grs-fondo/40 border border-grs-borde"
             wire:key="bomba-{{ $i }}">

            {{-- Etiqueta bomba en mobile --}}
            <div class="sm:hidden flex items-center justify-between mb-1">
                <span class="text-xs font-bold text-grs-verde uppercase">Bomba {{ $i + 1 }}</span>
            </div>

            <div>
                <label class="label-grs sm:hidden">Número / ID</label>
                <input type="text" wire:model="bombas.{{ $i }}.numero"
                    placeholder="Ej: 10P-130"
                    class="input-grs text-sm font-mono"/>
            </div>
            <div>
                <label class="label-grs sm:hidden">Camisa / Diámetro</label>
                <input type="text" wire:model="bombas.{{ $i }}.camisa_diametro"
                    placeholder='Ej: 6½"'
                    class="input-grs text-sm font-mono"/>
            </div>
            <div>
                <label class="label-grs sm:hidden">Profundidad (ft)</label>
                <input type="number" wire:model="bombas.{{ $i }}.profundidad_ft"
                    step="0.01" min="0" placeholder="0.00"
                    inputmode="decimal"
                    class="input-grs text-sm font-mono text-center"/>
            </div>
            <div>
                <label class="label-grs sm:hidden">Peso Lodo (ppg)</label>
                <input type="number" wire:model="bombas.{{ $i }}.peso_lodo_ppg"
                    step="0.01" min="0" placeholder="0.00"
                    inputmode="decimal"
                    class="input-grs text-sm font-mono text-center"/>
            </div>
            <div>
                <label class="label-grs sm:hidden">SPM</label>
                <input type="number" wire:model="bombas.{{ $i }}.spm"
                    step="0.1" min="0" placeholder="0"
                    inputmode="decimal"
                    class="input-grs text-sm font-mono text-center"/>
            </div>
            <div>
                <label class="label-grs sm:hidden">Presión (PSI)</label>
                <input type="number" wire:model="bombas.{{ $i }}.presion_psi"
                    step="1" min="0" placeholder="0"
                    inputmode="decimal"
                    class="input-grs text-sm font-mono text-center"/>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ── SECCIÓN 3 y 4: Cable de Perforación + Diesel (lado a lado en desktop) ── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

    {{-- Cable de Perforación --}}
    <div class="card-grs">
        <h3 class="seccion-titulo">
            <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
            </svg>
            Cable de Perforación
        </h3>

        <div class="grid grid-cols-2 gap-3">
            <div class="col-span-2">
                <label class="label-grs">Diámetro</label>
                <input type="text" wire:model="c_diametro"
                    placeholder='Ej: 1-1/8"'
                    class="input-grs font-mono"/>
            </div>
            <div>
                <label class="label-grs">Ton-Milla Acum.</label>
                <div class="relative">
                    <input type="number" wire:model="c_ton_milla_acumulada"
                        step="0.01" min="0" placeholder="0.00"
                        inputmode="decimal"
                        class="input-grs font-mono pr-7"/>
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-600">TM</span>
                </div>
            </div>
            <div>
                <label class="label-grs">TM del Día</label>
                <div class="relative">
                    <input type="number" wire:model="c_ton_milla_dia"
                        step="0.01" min="0" placeholder="0.00"
                        inputmode="decimal"
                        class="input-grs font-mono pr-7"/>
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-600">TM</span>
                </div>
            </div>
            <div class="col-span-2">
                <label class="label-grs">Sobrante</label>
                <div class="relative">
                    <input type="number" wire:model="c_sobrante_ft"
                        step="0.01" min="0" placeholder="0.00"
                        inputmode="decimal"
                        class="input-grs font-mono pr-7"/>
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-600">ft</span>
                </div>
            </div>
            <div class="col-span-2">
                <label class="label-grs">Comentarios</label>
                <textarea wire:model="c_comentarios"
                    rows="2" placeholder="Observaciones del cable..."
                    class="input-grs resize-none text-sm"></textarea>
            </div>
        </div>
    </div>

    {{-- Diesel --}}
    <div class="card-grs">
        <h3 class="seccion-titulo">
            <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Diesel
            <span class="text-[9px] normal-case font-normal text-gray-600 ml-1">(galones)</span>
        </h3>

        <div class="space-y-2">
            @php
            $camposDiesel = [
                ['key' => 'd_ayer',      'label' => 'Inventario Ayer',  'readonly' => false],
                ['key' => 'd_recibido',  'label' => 'Recibido Hoy',     'readonly' => false],
                ['key' => 'd_hoy',       'label' => 'Inventario Hoy',   'readonly' => false],
                ['key' => 'd_usado',     'label' => 'Usado (auto)',      'readonly' => true],
                ['key' => 'd_acumulado', 'label' => 'Acumulado',        'readonly' => false],
            ];
            @endphp

            @foreach($camposDiesel as $c)
            <div class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg border border-grs-borde/50">
                <label class="text-xs font-medium text-grs-texto flex-shrink-0 w-36">
                    {{ $c['label'] }}
                    @if($c['readonly'])
                        <span class="text-[9px] text-grs-verde ml-1">(auto)</span>
                    @endif
                </label>
                <div class="flex items-center gap-2 flex-1 justify-end">
                    @if($c['readonly'])
                        <input type="number"
                            value="{{ $this->{$c['key']} ?? '' }}"
                            step="0.01" placeholder="0.00"
                            inputmode="decimal"
                            readonly
                            class="input-grs font-mono text-right text-sm w-32"/>
                    @else
                        <input type="number"
                            wire:model.live="{{ $c['key'] }}"
                            step="0.01" min="0" placeholder="0.00"
                            inputmode="decimal"
                            class="input-grs font-mono text-right text-sm w-32"/>
                    @endif
                    <span class="text-xs text-gray-600 w-6">gal</span>
                </div>
            </div>
            @endforeach

            {{-- Indicador visual diesel --}}
            @if($d_ayer && $d_hoy)
            <div class="mt-3 pt-3 border-t border-grs-borde">
                @php $pct = $d_ayer > 0 ? min(100, (float)$d_hoy / (float)$d_ayer * 100) : 0; @endphp
                <div class="flex justify-between text-xs text-grs-texto mb-1">
                    <span>Nivel actual vs ayer</span>
                    <span class="font-mono font-bold {{ $pct < 25 ? 'text-red-400' : 'text-grs-verde' }}">
                        {{ number_format($pct, 1) }}%
                    </span>
                </div>
                <div class="h-2 bg-grs-borde rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500
                        {{ $pct < 25 ? 'bg-red-500' : ($pct < 50 ? 'bg-yellow-500' : 'bg-grs-verde') }}"
                        style="width: {{ $pct }}%">
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
