<x-app-layout>
    <x-slot name="titulo">Nuevo RIG</x-slot>

    <div class="max-w-lg">
        <div class="mb-4">
            <a href="{{ route('admin.rigs.index') }}" class="btn-grs-outline text-xs py-1.5 flex items-center gap-1 w-fit">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Volver
            </a>
        </div>

        <div class="card-grs">
            <h2 class="text-white font-semibold mb-4">Registrar RIG</h2>

            @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-500/20 border border-red-500/40 text-red-400 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.rigs.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="label-grs">Número de RIG * <span class="text-[9px] text-gray-400">(único)</span></label>
                    <input type="text" name="numero" value="{{ old('numero') }}"
                        placeholder="Ej: 158"
                        class="input-grs font-mono @error('numero') border-red-500 @enderror"/>
                </div>
                <div>
                    <label class="label-grs">Nombre / Descripción</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                        placeholder="Ej: Equipo GEFCO 500T"
                        class="input-grs @error('nombre') border-red-500 @enderror"/>
                </div>
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="activo" value="0">
                        <input type="checkbox" name="activo" value="1" checked
                            class="w-4 h-4 rounded border-grs-borde bg-grs-fondo text-grs-verde focus:ring-grs-verde"/>
                        <span class="text-sm text-grs-texto">RIG activo</span>
                    </label>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-grs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Crear RIG
                    </button>
                    <a href="{{ route('admin.rigs.index') }}" class="btn-grs-outline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
