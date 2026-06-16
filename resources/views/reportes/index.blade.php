@use('Illuminate\Support\Str')
<x-app-layout>
    <x-slot name="titulo">Reportes FGPO-002</x-slot>

    {{-- Flash message --}}
    @if(session('flash.banner'))
    <div class="mb-4 px-4 py-3 rounded-lg
        {{ session('flash.bannerStyle') === 'success' ? 'bg-grs-verde/20 border border-grs-verde/40 text-grs-verde' : 'bg-red-500/20 border border-red-500/40 text-red-400' }}
        text-sm flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('flash.banner') }}
    </div>
    @endif

    <div class="card-grs">
        {{-- Cabecera con filtros --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div>
                <h2 class="text-white font-semibold text-lg">Reportes Diarios</h2>
                <p class="text-grs-texto text-xs mt-0.5">{{ $reportes->total() }} reporte(s) encontrado(s)</p>
            </div>
            <a href="{{ route('reportes.crear') }}" class="btn-grs flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Reporte
            </a>
        </div>

        {{-- Tabla desktop --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-grs-borde">
                        <th class="tabla-header text-left pb-2">Fecha</th>
                        <th class="tabla-header text-left pb-2">RIG / Pozo</th>
                        <th class="tabla-header text-left pb-2">Operación</th>
                        <th class="tabla-header text-center pb-2">Ft. Perf.</th>
                        <th class="tabla-header text-center pb-2">Prof. Hoy</th>
                        <th class="tabla-header text-center pb-2">Estado</th>
                        <th class="tabla-header text-center pb-2">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-grs-borde/50">
                    @forelse($reportes as $r)
                    <tr class="hover:bg-grs-fondo/40 transition-colors group">
                        <td class="py-2.5 pr-3">
                            <span class="font-mono text-grs-texto text-xs">{{ $r->fecha?->format('d/m/Y') }}</span>
                        </td>
                        <td class="py-2.5 pr-3">
                            <div class="font-semibold text-white text-xs">RIG {{ $r->rig }}</div>
                            <div class="text-grs-texto text-[10px]">{{ $r->pozo }}</div>
                        </td>
                        <td class="py-2.5 pr-3">
                            <span class="text-grs-texto text-xs">{{ Str::limit($r->operacion_actual, 40) ?: '—' }}</span>
                        </td>
                        <td class="py-2.5 pr-3 text-center">
                            <span class="font-mono text-grs-verde text-xs font-bold">{{ $r->ft_perforados ? number_format($r->ft_perforados, 0) : '—' }}</span>
                        </td>
                        <td class="py-2.5 pr-3 text-center">
                            <span class="font-mono text-grs-texto text-xs">{{ $r->prof_hoy_ft ? number_format($r->prof_hoy_ft, 0).' ft' : '—' }}</span>
                        </td>
                        <td class="py-2.5 pr-3 text-center">
                            @if($r->estado === 'COMPLETADO')
                                <span class="badge-completado">Completado</span>
                            @else
                                <span class="badge-borrador">Borrador</span>
                            @endif
                        </td>
                        <td class="py-2.5 text-center">
                            <div class="flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('reportes.ver', $r->id) }}"
                                   class="p-1.5 rounded hover:bg-grs-acento text-grs-texto hover:text-white transition-colors"
                                   title="Ver reporte">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if($r->estado === 'BORRADOR')
                                <a href="{{ route('reportes.editar', $r->id) }}"
                                   class="p-1.5 rounded hover:bg-grs-acento text-grs-texto hover:text-white transition-colors"
                                   title="Editar">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @endif
                                <a href="{{ route('reportes.pdf', $r->id) }}" target="_blank"
                                   class="p-1.5 rounded hover:bg-red-500/30 text-grs-texto hover:text-red-400 transition-colors"
                                   title="Exportar PDF">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('reportes.excel', $r->id) }}"
                                   class="p-1.5 rounded hover:bg-green-500/30 text-grs-texto hover:text-green-400 transition-colors"
                                   title="Exportar Excel">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center">
                            <svg class="w-12 h-12 text-grs-borde mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-grs-texto text-sm">No hay reportes aún.</p>
                            <a href="{{ route('reportes.crear') }}" class="btn-grs text-sm mt-4 inline-flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Crear primer reporte
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Cards mobile --}}
        <div class="sm:hidden space-y-3">
            @forelse($reportes as $r)
            <div class="p-3 rounded-lg bg-grs-fondo/40 border border-gray-600">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <div class="font-semibold text-white text-sm">RIG {{ $r->rig }} — {{ $r->pozo }}</div>
                        <div class="font-mono text-grs-texto text-xs">{{ $r->fecha?->format('d/m/Y') }}</div>
                    </div>
                    @if($r->estado === 'COMPLETADO')
                        <span class="badge-completado">Completado</span>
                    @else
                        <span class="badge-borrador">Borrador</span>
                    @endif
                </div>
                <div class="text-grs-texto text-xs mb-3">{{ Str::limit($r->operacion_actual, 60) ?: 'Sin operación registrada' }}</div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('reportes.ver', $r->id) }}" class="btn-grs-outline text-xs py-1 px-3">Ver</a>
                    @if($r->estado === 'BORRADOR')
                    <a href="{{ route('reportes.editar', $r->id) }}" class="btn-grs-outline text-xs py-1 px-3">Editar</a>
                    @endif
                    <a href="{{ route('reportes.pdf', $r->id) }}" target="_blank" class="btn-grs-outline text-xs py-1 px-3">PDF</a>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-grs-texto text-sm">
                No hay reportes. <a href="{{ route('reportes.crear') }}" class="text-grs-verde underline">Crear uno</a>
            </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if($reportes->hasPages())
        <div class="mt-4 pt-4 border-t border-grs-borde">
            {{ $reportes->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
