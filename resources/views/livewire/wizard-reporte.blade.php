<div class="max-w-5xl mx-auto">

    {{-- ── Barra de progreso ── --}}
    <div class="card-grs mb-6 p-4">
        <div class="flex items-center justify-between">

            @php
            $pasos = [
                1 => ['label' => 'Encabezado',    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                2 => ['label' => 'Operaciones',   'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                3 => ['label' => 'Datos Técnicos','icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
                4 => ['label' => 'Equipo',        'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                5 => ['label' => 'Cierre',        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
            @endphp

            @foreach($pasos as $num => $info)

                {{-- Línea conectora --}}
                @if($num > 1)
                    <div class="flex-1 h-px mx-2 {{ $paso >= $num ? 'bg-grs-verde' : 'bg-grs-borde' }} transition-colors duration-300"></div>
                @endif

                {{-- Paso --}}
                <button
                    wire:click="irAPaso({{ $num }})"
                    class="flex flex-col items-center gap-1 group"
                    {{ ($num > $paso && !$reporteId) ? 'disabled' : '' }}>

                    {{-- Círculo --}}
                    <div class="w-9 h-9 rounded-full flex items-center justify-center transition-all duration-300 border-2
                        {{ $paso > $num  ? 'bg-grs-verde border-grs-verde text-grs-fondo' : '' }}
                        {{ $paso === $num ? 'bg-grs-fondo border-grs-verde text-grs-verde' : '' }}
                        {{ $paso < $num  ? 'bg-transparent border-grs-borde text-gray-600 group-hover:border-grs-acento' : '' }}">

                        @if($paso > $num)
                            {{-- Check completado --}}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            <span class="text-xs font-bold">{{ $num }}</span>
                        @endif
                    </div>

                    {{-- Etiqueta --}}
                    <span class="text-[10px] font-medium hidden sm:block
                        {{ $paso === $num ? 'text-grs-verde' : ($paso > $num ? 'text-grs-texto' : 'text-gray-600') }}">
                        {{ $info['label'] }}
                    </span>
                </button>

            @endforeach
        </div>

        {{-- Barra de progreso lineal --}}
        <div class="mt-4 h-1 bg-grs-borde rounded-full overflow-hidden">
            <div class="h-full bg-grs-verde rounded-full transition-all duration-500"
                 style="width: {{ (($paso - 1) / ($totalPasos - 1)) * 100 }}%"></div>
        </div>
    </div>

    {{-- ── Indicador de guardado ── --}}
    @if($mensajeGuardado)
        <div class="flex items-center justify-end gap-2 mb-3 text-xs text-grs-texto">
            <div class="w-1.5 h-1.5 rounded-full bg-grs-verde animate-pulse"></div>
            {{ $mensajeGuardado }}
        </div>
    @endif

    {{-- ── Contenido del paso actual ── --}}
    <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">

        @if($paso === 1) @include('livewire.wizard.paso1') @endif
        @if($paso === 2) @include('livewire.wizard.paso2') @endif
        @if($paso === 3) @include('livewire.wizard.paso3') @endif
        @if($paso === 4) @include('livewire.wizard.paso4') @endif
        @if($paso === 5) @include('livewire.wizard.paso5') @endif

    </div>

    {{-- ── Navegación inferior ── --}}
    <div class="flex items-center justify-between mt-6 pt-4 border-t border-grs-borde">

        {{-- Botón Anterior --}}
        <div>
            @if($paso > 1)
                <button wire:click="anteriorPaso" class="btn-grs-outline flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Anterior
                </button>
            @endif
        </div>

        {{-- Centro: indicador de guardado en proceso --}}
        <div wire:loading class="flex items-center gap-2 text-xs text-grs-verde">
            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            Guardando...
        </div>

        {{-- Botón Siguiente / Completar --}}
        <div>
            @if($paso < $totalPasos)
                <button wire:click="siguientePaso" class="btn-grs flex items-center gap-2">
                    Siguiente
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @else
                <button wire:click="completarReporte" class="btn-grs flex items-center gap-2 px-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Completar Reporte
                </button>
            @endif
        </div>

    </div>

</div>
