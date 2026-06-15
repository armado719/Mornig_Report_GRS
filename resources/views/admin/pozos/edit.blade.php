<x-app-layout>
    <x-slot name="titulo">Editar Pozo</x-slot>

    <div class="max-w-2xl">
        <div class="mb-4">
            <a href="{{ route('admin.pozos.index') }}" class="btn-grs-outline text-xs py-1.5 flex items-center gap-1 w-fit">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>
        </div>

        <div class="card-grs">
            <h2 class="text-white font-semibold mb-4">Editar Pozo</h2>

            @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-500/20 border border-red-500/40 text-red-400 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.pozos.update', $pozo->id) }}" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="label-grs">Nombre del Pozo *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $pozo->nombre) }}"
                        class="input-grs @error('nombre') border-red-500 @enderror"/>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label-grs">Operador</label>
                        <input type="text" name="operador" value="{{ old('operador', $pozo->operador) }}"
                            class="input-grs"/>
                    </div>
                    <div>
                        <label class="label-grs">Municipio</label>
                        <input type="text" name="municipio" value="{{ old('municipio', $pozo->municipio) }}"
                            class="input-grs"/>
                    </div>
                    <div>
                        <label class="label-grs">Departamento</label>
                        <input type="text" name="departamento" value="{{ old('departamento', $pozo->departamento) }}"
                            class="input-grs"/>
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="activo" value="0">
                            <input type="checkbox" name="activo" value="1"
                                {{ old('activo', $pozo->activo ? '1' : '0') === '1' ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-grs-borde bg-grs-fondo text-grs-verde focus:ring-grs-verde"/>
                            <span class="text-sm text-grs-texto">Pozo activo</span>
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
                    <a href="{{ route('admin.pozos.index') }}" class="btn-grs-outline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
