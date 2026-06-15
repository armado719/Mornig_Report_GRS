<x-guest-layout>
    <x-auth-session-status class="mb-4 p-3 bg-grs-acento/20 border border-grs-acento rounded-lg text-grs-verde text-sm" :status="session('status')"/>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="label-grs">Correo electrónico</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="usuario@grs.com"
                class="w-full rounded-lg px-3 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 focus:outline-none focus:ring-2 focus:ring-grs-verde @error('email') border-red-400 @enderror"
            />
            @error('email')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div>
            <label for="password" class="label-grs">Contraseña</label>
            <div class="relative">
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full rounded-lg px-3 py-2.5 text-sm text-gray-800 bg-white border border-gray-200 focus:outline-none focus:ring-2 focus:ring-grs-verde pr-10 @error('password') border-red-400 @enderror"
                />
                {{-- Toggle mostrar contraseña --}}
                <button type="button"
                    onclick="const i=document.getElementById('password');i.type=i.type==='password'?'text':'password'"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-grs-verde transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                               -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Recordarme --}}
        <div class="flex items-center gap-2">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="w-4 h-4 rounded border-grs-borde bg-grs-fondo text-grs-verde
                       focus:ring-grs-acento focus:ring-offset-grs-fondo"
            />
            <label for="remember_me" class="text-sm text-grs-texto cursor-pointer">
                Mantener sesión iniciada
            </label>
        </div>

        {{-- Botón --}}
        <button type="submit" class="w-full py-3 text-base font-bold rounded-lg transition-all mt-2"
                style="background:#6DBE6D; color:#0D1F17;">
            Iniciar sesión
        </button>

        @if (Route::has('password.request'))
            <div class="text-center">
                <a href="{{ route('password.request') }}"
                   class="text-xs text-grs-texto hover:text-grs-verde transition-colors underline underline-offset-2">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
