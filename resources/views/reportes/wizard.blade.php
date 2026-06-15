<x-app-layout>
    <x-slot name="titulo">Nuevo Reporte — FGPO-002</x-slot>

    @livewire('wizard-reporte', ['id' => $id ?? null])

</x-app-layout>
