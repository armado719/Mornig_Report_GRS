<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($titulo) ? strip_tags($titulo).' — '.config('app.name') : config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900|jetbrains-mono:400,700&display=swap" rel="stylesheet"/>

    <link rel="stylesheet" href="/build/assets/app-DQd8rAbh.css">
    <script type="module" src="/build/assets/app-DO2nEFzp.js" defer></script>
    @livewireStyles

    <style>
        .toast-card {
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25), 0 4px 10px rgba(0,0,0,0.15);
        }
        .toast-card-success { background: #22c55e; }
        .toast-card-error   { background: #ef4444; }
        .toast-card-warning { background: #f59e0b; }
        .toast-card-info    { background: #3b82f6; }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('confirmModal', {
                open: false,
                message: '',
                _callback: null,
                show(message, callback) {
                    this.message = message;
                    this._callback = callback;
                    this.open = true;
                },
                confirm() {
                    if (this._callback) this._callback();
                    this.open = false;
                },
                cancel() {
                    this.open = false;
                }
            });

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
            <div class="flex flex-col items-center gap-2 px-5 py-5 border-b border-grs-borde flex-shrink-0">
                <x-grs-logo size="xl"/>
                <div class="flex flex-col items-center leading-tight">
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
                    <p class="px-3 mb-1 text-[10px] font-bold uppercase tracking-widest text-gray-300">Administración</p>

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
                                RIGS
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
                            class="text-gray-400 hover:text-red-400 transition-colors"
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

    {{-- ===== MODAL DE CONFIRMACIÓN ===== --}}
    <div x-data x-show="$store.confirmModal.open"
         style="display:none; position:fixed; inset:0; z-index:9999;">

        {{-- Overlay oscuro --}}
        <div style="position:absolute; inset:0; background:rgba(0,0,0,0.65); backdrop-filter:blur(4px);"
             @click="$store.confirmModal.cancel()"></div>

        {{-- Tarjeta centrada --}}
        <div x-show="$store.confirmModal.open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
                    width:320px; background:#1B4D35; border-radius:1.25rem;
                    border:1px solid #4B5563; box-shadow:0 25px 60px rgba(0,0,0,0.6);">

            {{-- Icono y texto --}}
            <div style="display:flex; flex-direction:column; align-items:center; padding:2rem 1.5rem 1rem; text-align:center;">
                <div style="width:3.5rem; height:3.5rem; border-radius:50%; background:rgba(239,68,68,0.15);
                            border:2px solid rgba(239,68,68,0.4); display:flex; align-items:center;
                            justify-content:center; margin-bottom:1rem;">
                    <svg style="width:1.75rem; height:1.75rem;" fill="none" viewBox="0 0 24 24"
                         stroke="#f87171" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <p style="color:white; font-weight:700; font-size:1rem; margin-bottom:0.5rem;">¿Confirmar acción?</p>
                <p style="color:#D1D5DB; font-size:0.875rem; line-height:1.4;" x-text="$store.confirmModal.message"></p>
            </div>

            {{-- Botones --}}
            <div style="display:flex; gap:0.75rem; padding:1rem 1.5rem 1.5rem;">
                <button @click="$store.confirmModal.cancel()"
                        style="flex:1; padding:0.6rem 0; border-radius:0.75rem; border:1px solid #6B7280;
                               color:#D1D5DB; font-size:0.875rem; font-weight:500; background:transparent; cursor:pointer;">
                    Cancelar
                </button>
                <button @click="$store.confirmModal.confirm()"
                        style="flex:1; padding:0.6rem 0; border-radius:0.75rem; border:none;
                               color:white; font-size:0.875rem; font-weight:700; background:#ef4444; cursor:pointer;">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    {{-- ===== TOAST CONTAINER ===== --}}
    <div x-data class="fixed bottom-6 right-6 z-50 flex flex-col gap-3" style="min-width:320px; max-width:400px;">
        <template x-for="toast in $store.toasts.items" :key="toast.id">
            <div x-show="toast.show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-6 scale-90"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-90"
                 class="toast-card flex items-start gap-3 px-5 py-4 text-white"
                 :class="{
                     'toast-card-success': toast.type === 'success',
                     'toast-card-error':   toast.type === 'error',
                     'toast-card-warning': toast.type === 'warning',
                     'toast-card-info':    toast.type === 'info',
                 }">

                {{-- Icono circular --}}
                <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center"
                     style="background:rgba(255,255,255,0.25);">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            x-bind:d="
                                toast.type === 'success' ? 'M5 13l4 4L19 7' :
                                toast.type === 'error'   ? 'M6 18L18 6M6 6l12 12' :
                                toast.type === 'warning' ? 'M12 9v4m0 4h.01' :
                                'M13 16h-1v-4h-1m1-4h.01'
                            "/>
                    </svg>
                </div>

                {{-- Contenido --}}
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-sm leading-tight mb-0.5"
                       x-text="toast.type === 'success' ? 'Éxito' : toast.type === 'error' ? 'Error' : toast.type === 'warning' ? 'Atención' : 'Información'">
                    </p>
                    <p class="text-sm leading-snug" style="opacity:0.92;" x-text="toast.message"></p>
                </div>

                {{-- Cerrar --}}
                <button @click="$store.toasts.remove(toast.id)"
                        class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center transition-all"
                        style="background:rgba(255,255,255,0.2);"
                        onmouseover="this.style.background='rgba(255,255,255,0.35)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>
</body>
</html>
