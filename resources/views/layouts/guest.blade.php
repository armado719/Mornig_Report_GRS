<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Acceso</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900|jetbrains-mono:400,700&display=swap" rel="stylesheet"/>

    <link rel="stylesheet" href="/build/assets/app-DvmndZ3c.css">
    <script type="module" src="/build/assets/app-DO2nEFzp.js" defer></script>
    @livewireStyles
</head>
<body class="h-full font-sans antialiased" style="background:#061209;">

    <div class="min-h-screen flex items-center justify-start relative overflow-hidden"
         style="background: linear-gradient(135deg, #061209 0%, #0D1F17 50%, #0a1a10 100%);">

        {{-- Imagen de fondo --}}
        @if(file_exists(public_path('images/bg-login.jpg')))
        <div class="absolute inset-0 bg-cover bg-center"
             style="background-image:url('/images/bg-login.jpg');"></div>
        <div class="absolute inset-0" style="background:rgba(6,18,9,0.72);"></div>
        @endif

        {{-- Patrón de fondo decorativo --}}
        <div class="absolute inset-0 opacity-10" style="pointer-events:none;">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse">
                        <path d="M 60 0 L 0 0 0 60" fill="none" stroke="#6DBE6D" stroke-width="0.8"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)"/>
            </svg>
        </div>

        {{-- Círculo de luz decorativo derecho --}}
        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-96 h-96 rounded-full opacity-10"
             style="background:radial-gradient(circle, #2D7A4F 0%, transparent 70%); right:-4rem;"></div>
        <div class="absolute right-1/4 bottom-0 w-64 h-64 rounded-full opacity-5"
             style="background:radial-gradient(circle, #6DBE6D 0%, transparent 70%);"></div>

        {{-- Card de login --}}
        <div class="relative z-10 w-full max-w-sm mx-auto lg:ml-20 xl:ml-40 px-6 py-6">
            <div class="rounded-2xl p-8 shadow-2xl border"
                 style="background:rgba(27,77,53,0.55); backdrop-filter:blur(12px); border-color:rgba(109,190,109,0.25);">

                {{-- Logo y nombre --}}
                <div class="flex flex-col items-center mb-6">
                    <div class="w-20 h-20 rounded-full flex items-center justify-center mb-3 border-2"
                         style="background:rgba(13,31,23,0.8); border-color:#2D7A4F;">
                        <x-grs-logo size="md"/>
                    </div>
                    <h1 class="text-white font-bold text-xl tracking-wide">GRS</h1>
                    <p class="text-grs-verde text-xs text-center mt-0.5">General Rigs Services S.A.S.</p>
                    <div class="w-12 h-0.5 mt-3" style="background:#6DBE6D;"></div>
                </div>

                {{-- Formulario --}}
                {{ $slot }}

                {{-- Footer --}}
                <p class="text-center text-xs mt-6" style="color:rgba(209,213,219,0.4);">
                    Morning Report GRS © {{ date('Y') }}
                </p>
            </div>
        </div>

        {{-- Texto derecho decorativo (solo desktop) --}}
        <div class="hidden lg:flex flex-col justify-center flex-1 px-16 xl:px-24">
            <div class="max-w-md">
                <h2 class="text-3xl font-bold text-white mb-2">Morning Report</h2>
                <h3 class="text-grs-verde text-xl font-semibold mb-4">FGPO-002</h3>
                <p class="text-grs-texto text-sm leading-relaxed mb-8">
                    Sistema de gestión de reportes diarios de operaciones de perforación.
                </p>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-sm text-grs-texto">
                        <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#6DBE6D;"></div>
                        <span>Reporte Diario de Operaciones</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-grs-texto">
                        <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#6DBE6D;"></div>
                        <span>Exportación PDF y Excel certificados</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-grs-texto">
                        <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#6DBE6D;"></div>
                        <span>Control de RIGs y pozos en tiempo real</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @livewireScripts
</body>
</html>
