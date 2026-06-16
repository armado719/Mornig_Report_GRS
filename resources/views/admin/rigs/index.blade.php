<x-app-layout>
    <x-slot name="titulo">Administrar RIGS</x-slot>

    <div class="card-grs max-w-2xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-white font-semibold text-lg">RIGS</h2>
                <p class="text-grs-texto text-xs mt-0.5">{{ $rigs->total() }} RIG(s) registrado(s)</p>
            </div>
            <a href="{{ route('admin.rigs.create') }}" class="btn-grs flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo RIG
            </a>
        </div>

        <div class="space-y-2">
            @forelse($rigs as $rig)
            <div class="flex items-center justify-between px-4 py-3 rounded-lg bg-grs-fondo/40 border border-gray-600 group hover:border-grs-acento transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-grs-primario border border-gray-600 flex items-center justify-center">
                        <span class="font-mono font-bold text-grs-verde text-sm">{{ $rig->numero }}</span>
                    </div>
                    <div>
                        <div class="font-medium text-white text-sm">{{ $rig->nombre ?: 'RIG '.$rig->numero }}</div>
                        <div class="text-grs-texto text-xs">Número: {{ $rig->numero }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($rig->activo)
                        <span class="badge-completado">Activo</span>
                    @else
                        <span class="badge-borrador">Inactivo</span>
                    @endif
                    <div class="flex gap-1">
                        <a href="{{ route('admin.rigs.edit', $rig->id) }}"
                           class="p-1.5 rounded hover:bg-grs-acento text-gray-400 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.rigs.destroy', $rig->id) }}" id="form-rig-{{ $rig->id }}">
                            @csrf @method('DELETE')
                            <button type="button"
                                    @click="$store.confirmModal.show('¿Desactivar RIG {{ $rig->numero }}?', () => document.getElementById('form-rig-{{ $rig->id }}').submit())"
                                    class="p-1.5 rounded hover:bg-red-500/30 text-gray-400 hover:text-red-400 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-grs-texto text-sm">No hay RIGS registrados.</div>
            @endforelse
        </div>

        @if($rigs->hasPages())
        <div class="mt-4 pt-4 border-t border-grs-borde">{{ $rigs->links() }}</div>
        @endif
    </div>
</x-app-layout>
