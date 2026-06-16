<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Acceso</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900|jetbrains-mono:400,700&display=swap" rel="stylesheet"/>

    <link rel="stylesheet" href="/build/assets/app-Lq1AQZfx.css">
    <script type="module" src="/build/assets/app-DO2nEFzp.js" defer></script>
    @livewireStyles

    <style>
        @keyframes kenBurns {
            0%   { transform: scale(1.0) translate(0%, 0%); }
            25%  { transform: scale(1.07) translate(-1%, -0.5%); }
            50%  { transform: scale(1.1) translate(0.5%, -1%); }
            75%  { transform: scale(1.07) translate(-0.5%, 0.5%); }
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

    <div class="min-h-screen flex items-center relative overflow-hidden"
         style="background: linear-gradient(135deg, #061209 0%, #0D1F17 50%, #0a1a10 100%);">

        {{-- Imagen de fondo con animación Ken Burns --}}
        @if(file_exists(public_path('images/bg-login.jpg')))
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute bg-ken-burns bg-cover"
                 style="inset:-6%; background-image:url('/images/bg-login.jpg'); background-position:38% center;"></div>
            {{-- Overlay: oscuro a los lados, semitransparente al centro --}}
            <div class="absolute inset-0"
                 style="background: linear-gradient(to right, rgba(4,14,8,0.25) 0%, rgba(4,14,8,0.1) 40%, rgba(4,14,8,0.75) 70%, rgba(4,14,8,0.92) 100%);"></div>
        </div>
        @endif

        {{-- Patrón decorativo (muy sutil) --}}
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

        {{-- Espacio izquierdo (trabajadores visibles) --}}
        <div class="hidden lg:flex flex-col justify-end flex-1 px-10 pb-12">
            <div style="text-shadow:0 2px 12px rgba(0,0,0,0.9);">
                <p class="text-grs-verde text-xs font-bold uppercase tracking-widest mb-1">General Rigs Services S.A.S.</p>
                <h2 class="text-4xl font-black text-white mb-1">Morning Report</h2>
                <p class="text-grs-verde text-lg font-semibold">FGPO-002</p>
            </div>
        </div>

        {{-- Card de login — lado derecho --}}
        <div class="relative z-10 w-full max-w-xs mx-4 sm:mx-auto lg:mx-0 lg:mr-16 xl:mr-24 flex-shrink-0">
            <div class="shadow-2xl"
                 style="border-radius:2rem; background:rgba(10,24,15,0.88); backdrop-filter:blur(20px); border:1px solid rgba(109,190,109,0.25);">

                {{-- Franja verde superior --}}
                <div style="height:3px; background:linear-gradient(90deg,#2D7A4F,#6DBE6D,#2D7A4F); border-radius:2rem 2rem 0 0;"></div>

                <div class="px-7 py-8">
                    {{-- Logo y nombre --}}
                    <div class="flex flex-col items-center mb-8">
                        @if(file_exists(public_path('images/grs-logo.png')))
                            <img src="/images/grs-logo.png" alt="GRS"
                                 class="w-20 h-20 rounded-full object-cover mb-4 shadow-xl"
                                 style="border:2px solid rgba(109,190,109,0.5); box-shadow:0 0 24px rgba(45,122,79,0.4);">
                        @else
                            <div class="w-20 h-20 rounded-full flex items-center justify-center mb-4 border-2"
                                 style="background:rgba(13,31,23,0.8); border-color:#2D7A4F;">
                                <x-grs-logo size="md"/>
                            </div>
                        @endif
                        <h1 class="text-white font-bold text-lg tracking-widest">GRS</h1>
                        <p class="text-grs-verde text-xs text-center mt-0.5 tracking-wide">General Rigs Services S.A.S.</p>
                        <div class="flex items-center gap-2 mt-3">
                            <div class="h-px w-8" style="background:rgba(109,190,109,0.3);"></div>
                            <div class="w-1.5 h-1.5 rounded-full" style="background:#6DBE6D;"></div>
                            <div class="h-px w-8" style="background:rgba(109,190,109,0.3);"></div>
                        </div>
                    </div>

                    {{-- Formulario --}}
                    {{ $slot }}

                    {{-- Footer --}}
                    <p class="text-center text-xs mt-8" style="color:rgba(209,213,219,0.3);">
                        Morning Report GRS © {{ date('Y') }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    @livewireScripts
</body>
</html>
