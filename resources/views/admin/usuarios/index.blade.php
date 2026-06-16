<x-app-layout>
    <x-slot name="titulo">Administrar Usuarios</x-slot>

    <div class="card-grs">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-white font-semibold text-lg">Usuarios</h2>
                <p class="text-grs-texto text-xs mt-0.5">{{ $usuarios->total() }} usuario(s) registrado(s)</p>
            </div>
            <a href="{{ route('admin.usuarios.create') }}" class="btn-grs flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Nuevo Usuario
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-grs-borde">
                        <th class="tabla-header text-left pb-2">Nombre</th>
                        <th class="tabla-header text-left pb-2">Email</th>
                        <th class="tabla-header text-center pb-2">Rol</th>
                        <th class="tabla-header text-center pb-2">RIG</th>
                        <th class="tabla-header text-center pb-2">Estado</th>
                        <th class="tabla-header text-center pb-2">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-grs-borde/50">
                    @forelse($usuarios as $u)
                    <tr class="hover:bg-grs-fondo/40 transition-colors group">
                        <td class="py-2.5 pr-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-grs-acento flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-grs-verde">{{ strtoupper(substr($u->nombre, 0, 1)) }}</span>
                                </div>
                                <span class="font-medium text-white">{{ $u->nombre }}</span>
                            </div>
                        </td>
                        <td class="py-2.5 pr-3 text-grs-texto text-xs">{{ $u->email }}</td>
                        <td class="py-2.5 pr-3 text-center">
                            @foreach($u->roles as $rol)
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold
                                {{ $rol->name === 'ADMIN' ? 'bg-purple-900/50 text-purple-300 border border-purple-700/50' : 'bg-grs-primario text-grs-verde border border-gray-600' }}">
                                {{ $rol->name }}
                            </span>
                            @endforeach
                        </td>
                        <td class="py-2.5 pr-3 text-center">
                            <span class="font-mono text-grs-verde text-xs">{{ $u->rig ? 'RIG '.$u->rig : '—' }}</span>
                        </td>
                        <td class="py-2.5 pr-3 text-center">
                            @if($u->activo)
                                <span class="badge-completado">Activo</span>
                            @else
                                <span class="badge-borrador">Inactivo</span>
                            @endif
                        </td>
                        <td class="py-2.5 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.usuarios.edit', $u->id) }}"
                                   class="p-1.5 rounded hover:bg-grs-acento text-gray-400 hover:text-white transition-colors"
                                   title="Editar">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.usuarios.destroy', $u->id) }}"
                                      onsubmit="return confirm('¿Desactivar a {{ $u->nombre }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded hover:bg-red-500/30 text-gray-400 hover:text-red-400 transition-colors" title="Desactivar">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="py-10 text-center text-grs-texto text-sm">No hay usuarios registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($usuarios->hasPages())
        <div class="mt-4 pt-4 border-t border-grs-borde">{{ $usuarios->links() }}</div>
        @endif
    </div>
</x-app-layout>
