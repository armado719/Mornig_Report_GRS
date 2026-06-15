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

    <style>
        @keyframes kenBurns {
            0%   { transform: scale(1.0) translate(0%, 0%); }
            25%  { transform: scale(1.08) translate(-1.5%, -1%); }
            50%  { transform: scale(1.12) translate(1%, -1.5%); }
            75%  { transform: scale(1.08) translate(-1%, 1%); }
            100% { transform: scale(1.0) translate(0%, 0%); }
        }
        .bg-ken-burns {
            animation: kenBurns 30s ease-in-out infinite;
            will-change: transform;
            transform-origin: center center;
        }
    </style>
</head>
<body class="h-full font-sans antialiased" style="background:#061209;">

    <div class="min-h-screen flex items-center justify-start relative overflow-hidden"
         style="background: linear-gradient(135deg, #061209 0%, #0D1F17 50%, #0a1a10 100%);">

        {{-- Imagen de fondo con animación Ken Burns --}}
        @if(file_exists(public_path('images/bg-login.jpg')))
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute bg-ken-burns bg-cover bg-center"
                 style="inset:-6%; background-image:url('/images/bg-login.jpg');"></div>
            <div class="absolute inset-0" style="background:rgba(4,14,8,0.68);"></div>
        </div>
        @endif

        {{-- Patrón de fondo decorativo --}}
        <div class="absolute inset-0 opacity-5" style="pointer-events:none;">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse">
                        <path d="M 60 0 L 0 0 0 60" fill="none" stroke="#6DBE6D" stroke-width="0.8"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)"/>
            </svg>
        </div>

        {{-- Card de login --}}
        <div class="relative z-10 w-full max-w-sm mx-auto lg:ml-20 xl:ml-40 px-6 py-6">
            <div class="rounded-2xl p-8 shadow-2xl border"
                 style="background:rgba(13,31,23,0.72); backdrop-filter:blur(16px); border-color:rgba(109,190,109,0.2);">

                {{-- Logo y nombre --}}
                <div class="flex flex-col items-center mb-6">
                    @if(file_exists(public_path('images/grs-logo.png')))
                        <img src="/images/grs-logo.png" alt="GRS"
                             class="w-20 h-20 rounded-full object-cover mb-3 shadow-lg"
                             style="border:2px solid rgba(109,190,109,0.4);">
                    @else
                        <div class="w-20 h-20 rounded-full flex items-center justify-center mb-3 border-2"
                             style="background:rgba(13,31,23,0.8); border-color:#2D7A4F;">
                            <x-grs-logo size="md"/>
                        </div>
                    @endif
                    <h1 class="text-white font-bold text-xl tracking-wide">GRS</h1>
                    <p class="text-grs-verde text-xs text-center mt-0.5">General Rigs Services S.A.S.</p>
                    <div class="w-12 h-0.5 mt-3" style="background:#6DBE6D;"></div>
                </div>

                {{-- Formulario --}}
                {{ $slot }}

                {{-- Footer --}}
                <p class="text-center text-xs mt-6" style="color:rgba(209,213,219,0.35);">
                    Morning Report GRS © {{ date('Y') }}
                </p>
            </div>
        </div>

        {{-- Texto derecho decorativo (solo desktop) --}}
        <div class="hidden lg:flex flex-col justify-center flex-1 px-16 xl:px-24">
            <div class="max-w-md">
                <h2 class="text-3xl font-bold text-white mb-2" style="text-shadow:0 2px 12px rgba(0,0,0,0.8);">Morning Report</h2>
                <h3 class="text-grs-verde text-xl font-semibold mb-4">FGPO-002</h3>
                <p class="text-grs-texto text-sm leading-relaxed mb-8" style="text-shadow:0 1px 6px rgba(0,0,0,0.7);">
                    Sistema de gestión de reportes diarios de operaciones de perforación.
                </p>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-sm text-grs-texto">
                        <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#6DBE6D;"></div>
                        <span style="text-shadow:0 1px 4px rgba(0,0,0,0.7);">Reporte Diario de Operaciones</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-grs-texto">
                        <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#6DBE6D;"></div>
                        <span style="text-shadow:0 1px 4px rgba(0,0,0,0.7);">Exportación PDF y Excel certificados</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-grs-texto">
                        <div class="w-2 h-2 rounded-full flex-shrink-0" style="background:#6DBE6D;"></div>
                        <span style="text-shadow:0 1px 4px rgba(0,0,0,0.7);">Control de RIGs y pozos en tiempo real</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @livewireScripts
</body>
</html>
