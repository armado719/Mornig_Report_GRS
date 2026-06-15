<x-app-layout>
    <x-slot name="titulo">Administrar Pozos</x-slot>

    @if(session('flash.banner'))
    <div class="mb-4 px-4 py-3 rounded-lg text-sm flex items-center gap-2
        {{ session('flash.bannerStyle') === 'success' ? 'bg-grs-verde/20 border border-grs-verde/40 text-grs-verde' : 'bg-red-500/20 border border-red-500/40 text-red-400' }}">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('flash.banner') }}
    </div>
    @endif

    <div class="card-grs">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-white font-semibold text-lg">Pozos</h2>
                <p class="text-grs-texto text-xs mt-0.5">{{ $pozos->total() }} pozo(s) registrado(s)</p>
            </div>
            <a href="{{ route('admin.pozos.create') }}" class="btn-grs flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Pozo
            </a>
        </div>

        <div class="hidden sm:grid grid-cols-[1fr_8rem_8rem_8rem_6rem_5rem] gap-2 px-2 mb-1">
            <span class="tabla-header">Nombre</span>
            <span class="tabla-header">Operador</span>
            <span class="tabla-header">Municipio</span>
            <span class="tabla-header">Departamento</span>
            <span class="tabla-header text-center">Estado</span>
            <span class="tabla-header text-center">Acciones</span>
        </div>

        <div class="space-y-1.5">
            @forelse($pozos as $pozo)
            <div class="grid grid-cols-1 sm:grid-cols-[1fr_8rem_8rem_8rem_6rem_5rem] gap-2 items-center
                        px-2 py-2.5 rounded-lg hover:bg-grs-fondo/30 group transition-colors border border-transparent hover:border-grs-borde">
                <div class="font-medium text-white text-sm">{{ $pozo->nombre }}</div>
                <div class="text-grs-texto text-xs">{{ $pozo->operador ?: '—' }}</div>
                <div class="text-grs-texto text-xs">{{ $pozo->municipio ?: '—' }}</div>
                <div class="text-grs-texto text-xs">{{ $pozo->departamento ?: '—' }}</div>
                <div class="text-center">
                    @if($pozo->activo)
                        <span class="badge-completado">Activo</span>
                    @else
                        <span class="badge-borrador">Inactivo</span>
                    @endif
                </div>
                <div class="flex gap-1 justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <a href="{{ route('admin.pozos.edit', $pozo->id) }}"
                       class="p-1.5 rounded hover:bg-grs-acento text-grs-texto hover:text-white transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('admin.pozos.destroy', $pozo->id) }}"
                          onsubmit="return confirm('¿Desactivar «{{ $pozo->nombre }}»?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 rounded hover:bg-red-500/30 text-grs-texto hover:text-red-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-grs-texto text-sm">No hay pozos registrados.</div>
            @endforelse
        </div>

        @if($pozos->hasPages())
        <div class="mt-4 pt-4 border-t border-grs-borde">{{ $pozos->links() }}</div>
        @endif
    </div>
</x-app-layout>
