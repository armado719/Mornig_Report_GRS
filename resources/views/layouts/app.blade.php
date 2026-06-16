<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($titulo) ? strip_tags($titulo).' — '.config('app.name') : config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900|jetbrains-mono:400,700&display=swap" rel="stylesheet"/>

    <link rel="stylesheet" href="/build/assets/app-C-0Z9bE1.css">
    <script type="module" src="/build/assets/app-DO2nEFzp.js" defer></script>
    @livewireStyles

    <style>
        .toast-bubble::after {
            content: '';
            position: absolute;
            bottom: -7px;
            left: 20px;
            border-left: 7px solid transparent;
            border-right: 7px solid transparent;
        }
        .toast-success::after { border-top: 7px solid rgba(45,122,79,0.9); }
        .toast-error::after   { border-top: 7px solid rgba(185,28,28,0.9); }
        .toast-warning::after { border-top: 7px solid rgba(180,83,9,0.9); }
        .toast-info::after    { border-top: 7px solid rgba(30,64,175,0.9); }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('toasts', {
                items: [],
                add(type, message, duration = 4500) {
                    const id = Date.now() + Math.random();
                    this.items.push({ id, type, message, show: true });
                    setTimeout(() => this.remove(id), duration);
                },
                remove(id) {
                    const t = this.items.find(x => x.id === id);
                    if (t) t.show = false;
                    setTimeout(() => { this.items = this.items.filter(x => x.id !== id); }, 400);
                }
            });
        });
        window.addEventListener('toast', e => {
            const d = e.detail[0] ?? e.detail;
            Alpine.store('toasts').add(d.type ?? 'info', d.message ?? d);
        });
    </script>
</head>
<body class="h-full font-sans antialiased bg-grs-fondo text-grs-texto"
      x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    <div class="flex h-screen overflow-hidden">

        {{-- ===== SIDEBAR ===== --}}
        <aside
            class="fixed inset-y-0 left-0 z-50 flex flex-col w-64 bg-grs-primario border-r border-grs-borde
                   transform transition-transform duration-300 ease-in-out
                   lg:relative lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-5 py-5 border-b border-grs-borde flex-shrink-0">
                <x-grs-logo size="sm"/>
                <div class="flex flex-col leading-tight">
                    <span class="text-white font-bold text-sm tracking-wide">Morning Report</span>
                    <span class="text-grs-texto text-[10px] uppercase tracking-widest">Operaciones</span>
                </div>
            </div>

            {{-- Navegación --}}
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                          {{ request()->routeIs('dashboard') ? 'bg-grs-acento text-white' : 'text-grs-texto hover:bg-grs-fondo hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                {{-- Reportes --}}
                <div x-data="{ open: {{ request()->routeIs('reportes.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                               {{ request()->routeIs('reportes.*') ? 'bg-grs-acento text-white' : 'text-grs-texto hover:bg-grs-fondo hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Reportes
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="ml-4 mt-1 space-y-1">
                        <a href="{{ route('reportes.index') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                                  {{ request()->routeIs('reportes.index') ? 'text-grs-verde' : 'text-grs-texto hover:text-white' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current flex-shrink-0"></span>
                            Ver reportes
                        </a>
                        <a href="{{ route('reportes.crear') }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                                  {{ request()->routeIs('reportes.crear') ? 'text-grs-verde' : 'text-grs-texto hover:text-white' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current flex-shrink-0"></span>
                            Nuevo reporte
                        </a>
                    </div>
                </div>

                {{-- Admin (solo ADMIN) --}}
                @role('ADMIN')
                <div class="pt-3">
                    <p class="px-3 mb-1 text-[10px] font-bold uppercase tracking-widest text-gray-600">Administración</p>

                    <div x-data="{ open: {{ request()->routeIs('admin.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                                   {{ request()->routeIs('admin.*') ? 'bg-grs-acento text-white' : 'text-grs-texto hover:bg-grs-fondo hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Configuración
                            </div>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="ml-4 mt-1 space-y-1">
                            <a href="{{ route('admin.usuarios.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                                      {{ request()->routeIs('admin.usuarios.*') ? 'text-grs-verde' : 'text-grs-texto hover:text-white' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current flex-shrink-0"></span>
                                Usuarios
                            </a>
                            <a href="{{ route('admin.rigs.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                                      {{ request()->routeIs('admin.rigs.*') ? 'text-grs-verde' : 'text-grs-texto hover:text-white' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current flex-shrink-0"></span>
                                RIGs
                            </a>
                            <a href="{{ route('admin.pozos.index') }}"
                               class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                                      {{ request()->routeIs('admin.pozos.*') ? 'text-grs-verde' : 'text-grs-texto hover:text-white' }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current flex-shrink-0"></span>
                                Pozos
                            </a>
                        </div>
                    </div>
                </div>
                @endrole

            </nav>

            {{-- Usuario en el pie del sidebar --}}
            <div class="flex-shrink-0 border-t border-grs-borde p-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-grs-acento flex items-center justify-center flex-shrink-0">
                        <span class="text-sm font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->nombre ?? 'U', 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->nombre }}</p>
                        <p class="text-[10px] text-grs-verde uppercase tracking-wider font-medium">
                            {{ auth()->user()->getRoleNames()->first() ?? '' }}
                            @if(auth()->user()->rig)
                                · RIG {{ auth()->user()->rig }}
                            @endif
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="text-gray-600 hover:text-red-400 transition-colors"
                            title="Cerrar sesión">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Overlay para mobile --}}
        <div x-show="sidebarOpen && window.innerWidth < 1024"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black/50 lg:hidden"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
        </div>

        {{-- ===== CONTENIDO PRINCIPAL ===== --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Header --}}
            <header class="flex-shrink-0 flex items-center justify-between h-14 px-4 lg:px-6
                           bg-grs-primario border-b border-grs-borde">

                {{-- Botón hamburguesa --}}
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-1.5 rounded-lg text-grs-texto hover:text-white hover:bg-grs-fondo transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Título de página --}}
                <div class="flex-1 ml-4">
                    @isset($titulo)
                        <h1 class="text-white font-semibold text-sm lg:text-base">{{ $titulo }}</h1>
                    @endisset
                </div>

                {{-- Acciones del header --}}
                <div class="flex items-center gap-3">
                    {{-- Fecha actual --}}
                    <span class="hidden sm:block text-xs text-grs-texto font-mono">
                        {{ now()->locale('es')->isoFormat('dddd D MMM YYYY') }}
                    </span>

                    {{-- Botón nuevo reporte --}}
                    @if(!request()->routeIs('reportes.crear'))
                        <a href="{{ route('reportes.crear') }}" class="btn-grs py-1.5 px-3 text-sm hidden sm:inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nuevo reporte
                        </a>
                    @endif
                </div>
            </header>

            {{-- Flash → Toast automático --}}
            @if(session('flash.banner'))
            <div x-data x-init="
                $nextTick(() => $store.toasts.add(
                    '{{ session('flash.bannerStyle') === 'success' ? 'success' : (session('flash.bannerStyle') === 'danger' ? 'error' : 'warning') }}',
                    '{{ addslashes(session('flash.banner')) }}'
                ))">
            </div>
            @endif
            @if(session('success'))
            <div x-data x-init="$nextTick(() => $store.toasts.add('success', '{{ addslashes(session('success')) }}'))"></div>
            @endif
            @if(session('error'))
            <div x-data x-init="$nextTick(() => $store.toasts.add('error', '{{ addslashes(session('error')) }}'))"></div>
            @endif
            @if(session('warning'))
            <div x-data x-init="$nextTick(() => $store.toasts.add('warning', '{{ addslashes(session('warning')) }}'))"></div>
            @endif

            {{-- Contenido --}}
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 relative">
                @if(file_exists(public_path('images/rig-bg.jpg')))
                <div class="absolute inset-0 bg-cover bg-center pointer-events-none"
                     style="background-image:url('/images/rig-bg.jpg'); background-position:center 30%; opacity:0.18;"></div>
                @endif
                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </main>

        </div>
    </div>

    @livewireScripts

    {{-- ===== TOAST CONTAINER ===== --}}
    <div x-data class="fixed bottom-6 right-6 z-50 flex flex-col gap-2" style="min-width:300px; max-width:380px;">
        <template x-for="toast in $store.toasts.items" :key="toast.id">
            <div x-show="toast.show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                 class="relative toast-bubble flex items-start gap-3 px-4 py-3 rounded-xl shadow-2xl text-sm font-medium"
                 :class="{
                     'toast-success bg-grs-primario border border-grs-verde/50 text-grs-verde':   toast.type === 'success',
                     'toast-error   bg-red-950     border border-red-500/50   text-red-300':      toast.type === 'error',
                     'toast-warning bg-yellow-950  border border-yellow-500/50 text-yellow-300':  toast.type === 'warning',
                     'toast-info    bg-blue-950    border border-blue-500/50  text-blue-300':     toast.type === 'info',
                 }"
                 style="backdrop-filter:blur(12px);">

                {{-- Icono --}}
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        x-bind:d="
                            toast.type === 'success' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' :
                            toast.type === 'error'   ? 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z' :
                            toast.type === 'warning' ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' :
                            'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                        "/>
                </svg>

                {{-- Mensaje --}}
                <span class="flex-1 leading-relaxed" x-text="toast.message"></span>

                {{-- Cerrar --}}
                <button @click="$store.toasts.remove(toast.id)"
                        class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>
</body>
</html>
