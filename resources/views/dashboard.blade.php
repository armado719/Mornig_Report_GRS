<x-app-layout>
    <x-slot name="titulo">Dashboard — RIG {{ auth()->user()->rig ?? 'Todos' }}</x-slot>

    {{-- Hero Banner --}}
    <div class="relative -mx-4 lg:-mx-6 -mt-4 lg:-mt-6 mb-6 overflow-hidden" style="height:280px;">
        @if(file_exists(public_path('images/rig-hero.jpg')))
        <div class="absolute inset-0 bg-cover bg-center"
             style="background-image:url('/images/rig-hero.jpg'); background-position:center 40%;"></div>
        @else
        <div class="absolute inset-0" style="background:linear-gradient(135deg,#061209,#1B4D35);"></div>
        @endif

        {{-- Overlay gradiente --}}
        <div class="absolute inset-0"
             style="background:linear-gradient(to bottom, rgba(6,18,9,0.15) 0%, rgba(6,18,9,0.45) 55%, rgba(6,18,9,0.93) 100%);"></div>

        {{-- Contenido inferior --}}
        <div class="absolute inset-0 flex flex-col justify-end px-6 lg:px-8 pb-7">
            <p class="text-grs-verde text-xs font-bold uppercase tracking-widest mb-2">
                {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </p>
            <h2 class="text-3xl lg:text-4xl font-black text-white mb-1"
                style="text-shadow:0 2px 16px rgba(0,0,0,0.9);">
                Buen día, {{ explode(' ', auth()->user()->nombre)[0] }}
            </h2>
            <div class="flex items-center gap-3 mt-0.5">
                <p class="text-grs-verde text-sm font-semibold">Morning Report · FGPO-002</p>
                @if(auth()->user()->rig)
                    <span class="text-white/40 text-xs">·</span>
                    <p class="text-sm text-white/60">RIG {{ auth()->user()->rig }}</p>
                @endif
            </div>
        </div>

        {{-- Línea verde inferior --}}
        <div class="absolute bottom-0 left-0 right-0" style="height:1px; background:linear-gradient(90deg,transparent,rgba(109,190,109,0.5),transparent);"></div>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="card-grs">
            <p class="text-xs font-semibold uppercase tracking-wider text-grs-texto mb-2">Reportes Hoy</p>
            <p class="text-3xl font-bold text-grs-verde font-mono">{{ $reportesHoy }}</p>
            <p class="text-xs text-gray-600 mt-1">{{ now()->format('d/m/Y') }}</p>
        </div>
        <div class="card-grs">
            <p class="text-xs font-semibold uppercase tracking-wider text-grs-texto mb-2">Completados — Mes</p>
            <p class="text-3xl font-bold text-white font-mono">{{ $completadosMes }}</p>
            <p class="text-xs text-gray-600 mt-1">{{ now()->locale('es')->isoFormat('MMMM YYYY') }}</p>
        </div>
        <div class="card-grs">
            <p class="text-xs font-semibold uppercase tracking-wider text-grs-texto mb-2">Borradores</p>
            <p class="text-3xl font-bold {{ $borradores > 0 ? 'text-yellow-400' : 'text-white' }} font-mono">{{ $borradores }}</p>
            <p class="text-xs text-gray-600 mt-1">pendientes de completar</p>
        </div>
        <div class="card-grs">
            <p class="text-xs font-semibold uppercase tracking-wider text-grs-texto mb-2">Total Reportes</p>
            <p class="text-3xl font-bold text-white font-mono">{{ $totalReportes }}</p>
            <p class="text-xs text-gray-600 mt-1">en el sistema</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Acceso rápido --}}
        <div class="card-grs flex flex-col items-center justify-center py-10 text-center gap-4">
            <div class="w-14 h-14 rounded-full bg-grs-acento flex items-center justify-center">
                <svg class="w-7 h-7 text-grs-verde" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <h3 class="text-white font-semibold text-base">Crear Reporte de Hoy</h3>
                <p class="text-grs-texto text-sm mt-1">{{ now()->locale('es')->isoFormat('D [de] MMMM YYYY') }}</p>
            </div>
            <a href="{{ route('reportes.crear') }}" class="btn-grs px-8">Iniciar reporte</a>
        </div>

        {{-- Últimos reportes --}}
        <div class="card-grs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wide">Últimos Reportes</h3>
                <a href="{{ route('reportes.index') }}" class="text-xs text-grs-verde hover:underline">Ver todos →</a>
            </div>

            @forelse($ultimosReportes as $r)
            <a href="{{ route('reportes.ver', $r->id) }}"
               class="flex items-center justify-between py-2.5 border-b border-grs-borde/50 hover:bg-grs-fondo/30 px-1 rounded transition-colors group">
                <div>
                    <div class="text-white text-sm font-medium group-hover:text-grs-verde transition-colors">
                        RIG {{ $r->rig }} — {{ $r->pozo }}
                    </div>
                    <div class="text-grs-texto text-xs font-mono">{{ $r->fecha?->format('d/m/Y') }}</div>
                </div>
                <div class="flex items-center gap-2">
                    @if($r->ft_perforados)
                    <span class="font-mono text-grs-verde text-xs font-bold">{{ number_format($r->ft_perforados, 0) }} ft</span>
                    @endif
                    @if($r->estado === 'COMPLETADO')
                        <span class="badge-completado">OK</span>
                    @else
                        <span class="badge-borrador">Draft</span>
                    @endif
                </div>
            </a>
            @empty
            <div class="flex flex-col items-center justify-center py-8 text-center">
                <svg class="w-12 h-12 text-grs-borde mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-grs-texto text-sm">No hay reportes aún</p>
            </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
