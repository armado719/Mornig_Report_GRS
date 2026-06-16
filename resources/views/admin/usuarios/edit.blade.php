<x-app-layout>
    <x-slot name="titulo">Editar Usuario</x-slot>

    <div class="max-w-2xl">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('admin.usuarios.index') }}" class="btn-grs-outline text-xs py-1.5 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>
        </div>

        <div class="card-grs">
            <h2 class="text-white font-semibold mb-1">Editar Usuario</h2>
            <p class="text-grs-texto text-xs mb-4">{{ $usuario->email }}</p>

            @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-500/20 border border-red-500/40 text-red-400 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.usuarios.update', $usuario->id) }}" class="space-y-4">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label-grs">Nombre completo *</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}"
                            class="input-grs @error('nombre') border-red-500 @enderror"/>
                    </div>
                    <div>
                        <label class="label-grs">Email *</label>
                        <input type="email" name="email" value="{{ old('email', $usuario->email) }}"
                            class="input-grs @error('email') border-red-500 @enderror"/>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label-grs">Nueva contraseña <span class="text-[9px] text-gray-400">(dejar vacío para no cambiar)</span></label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="input-grs @error('password') border-red-500 @enderror"/>
                    </div>
                    <div>
                        <label class="label-grs">Rol *</label>
                        <select name="rol" class="input-grs @error('rol') border-red-500 @enderror">
                            <option value="">— Selecciona un rol —</option>
                            @foreach($roles as $rol)
                            <option value="{{ $rol }}" {{ old('rol', $usuario->roles->first()?->name) === $rol ? 'selected' : '' }}>{{ $rol }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label-grs">RIG asignado</label>
                        <select name="rig" class="input-grs">
                            <option value="">— Sin RIG asignado —</option>
                            @foreach($rigs as $num)
                            <option value="{{ $num }}" {{ old('rig', $usuario->rig) === $num ? 'selected' : '' }}>RIG {{ $num }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="activo" value="0">
                            <input type="checkbox" name="activo" value="1"
                                {{ old('activo', $usuario->activo ? '1' : '0') === '1' ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-grs-borde bg-grs-fondo text-grs-verde focus:ring-grs-verde"/>
                            <span class="text-sm text-grs-texto">Usuario activo</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-grs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Cambios
                    </button>
                    <a href="{{ route('admin.usuarios.index') }}" class="btn-grs-outline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
