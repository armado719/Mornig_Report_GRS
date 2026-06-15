<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Acceso</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900|jetbrains-mono:400,700&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased bg-grs-fondo">

    <div class="min-h-screen flex">

        {{-- Panel izquierdo — identidad GRS --}}
        <div class="hidden lg:flex lg:w-1/2 flex-col items-center justify-center
                    bg-grs-primario border-r border-grs-borde relative overflow-hidden px-12">

            {{-- Fondo decorativo --}}
            <div class="absolute inset-0 opacity-5">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#6DBE6D" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)"/>
                </svg>
            </div>

            <div class="relative z-10 flex flex-col items-center text-center gap-8">
                <x-grs-logo size="xl"/>

                <div class="space-y-3">
                    <h1 class="text-2xl font-bold text-white">
                        General Rigs Services S.A.S
                    </h1>
                    <p class="text-grs-verde italic text-base font-medium">
                        "Comprometidos con la excelencia, hoy y siempre"
                    </p>
                </div>

                <div class="border-t border-grs-borde pt-6 w-full space-y-3 text-sm text-grs-texto">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-grs-verde flex-shrink-0"></div>
                        <span>Reporte Diario de Operaciones — FGPO-002</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-grs-verde flex-shrink-0"></div>
                        <span>RIG 158 · Sistema de gestión digital</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-grs-verde flex-shrink-0"></div>
                        <span>Exportación PDF y Excel certificados</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel derecho — formulario --}}
        <div class="flex-1 flex flex-col items-center justify-center px-6 py-12 lg:px-16">

            {{-- Logo mobile --}}
            <div class="lg:hidden mb-8">
                <x-grs-logo size="lg"/>
            </div>

            <div class="w-full max-w-sm">
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="text-2xl font-bold text-white">Iniciar sesión</h2>
                    <p class="text-grs-texto text-sm mt-1">Ingresa tus credenciales para continuar</p>
                </div>

                {{ $slot }}
            </div>

            <p class="mt-10 text-xs text-gray-600 text-center">
                © {{ date('Y') }} General Rigs Services S.A.S — Uso interno
            </p>
        </div>
    </div>

    @livewireScripts
</body>
</html>
