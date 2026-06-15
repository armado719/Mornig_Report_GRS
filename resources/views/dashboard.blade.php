<x-app-layout>
    <x-slot name="titulo">Dashboard — RIG {{ auth()->user()->rig ?? 'Todos' }}</x-slot>

    {{-- Bienvenida --}}
    <div class="mb-6">
        <h2 class="text-xl font-bold text-white">
            Buen día, {{ explode(' ', auth()->user()->nombre)[0] }}
        </h2>
        <p class="text-grs-texto text-sm mt-1">
            {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
        </p>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="card-grs">
            <p class="text-xs font-semibold uppercase tracking-wider text-grs-texto mb-2">Reportes Hoy</p>
            <p class="text-3xl font-bold text-grs-verde font-mono">0</p>
            <p class="text-xs text-gray-600 mt-1">de 1 esperado</p>
        </div>
        <div class="card-grs">
            <p class="text-xs font-semibold uppercase tracking-wider text-grs-texto mb-2">Este Mes</p>
            <p class="text-3xl font-bold text-white font-mono">0</p>
            <p class="text-xs text-gray-600 mt-1">reportes completados</p>
        </div>
        <div class="card-grs">
            <p class="text-xs font-semibold uppercase tracking-wider text-grs-texto mb-2">Borradores</p>
            <p class="text-3xl font-bold text-yellow-400 font-mono">0</p>
            <p class="text-xs text-gray-600 mt-1">pendientes de completar</p>
        </div>
        <div class="card-grs">
            <p class="text-xs font-semibold uppercase tracking-wider text-grs-texto mb-2">PDFs Generados</p>
            <p class="text-3xl font-bold text-white font-mono">0</p>
            <p class="text-xs text-gray-600 mt-1">documentos exportados</p>
        </div>
    </div>

    {{-- Acceso rápido --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

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
            <a href="{{ route('reportes.crear') }}" class="btn-grs px-8">
                Iniciar reporte
            </a>
        </div>

        <div class="card-grs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wide">Últimos reportes</h3>
                <a href="{{ route('reportes.index') }}" class="text-xs text-grs-verde hover:underline">Ver todos</a>
            </div>
            <div class="flex flex-col items-center justify-center py-8 text-center">
                <svg class="w-12 h-12 text-grs-borde mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-grs-texto text-sm">No hay reportes aún</p>
                <p class="text-gray-600 text-xs mt-1">Los reportes aparecerán aquí</p>
            </div>
        </div>

    </div>
</x-app-layout>
