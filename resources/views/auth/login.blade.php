@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="relative w-full max-w-md">
        {{-- Background decoration --}}
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-amber-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-cyan-500/20 rounded-full blur-3xl"></div>

        {{-- Login card --}}
        <div class="relative">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-cyan-500/10 rounded-3xl"></div>
            <div class="absolute inset-0 glass rounded-3xl"></div>

            <div class="relative z-10 p-10">
                {{-- Logo/Icon --}}
                <div class="flex justify-center mb-8">
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-2xl animate-float">
                        <svg class="w-10 h-10 text-dark-950" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
                        </svg>
                    </div>
                </div>

                {{-- Title --}}
                <h1 class="font-display text-4xl tracking-wide text-center mb-3">BIENVENIDO</h1>
                <p class="text-gray-400 text-center mb-10">
                    Inicia sesión para guardar tus favoritos, ver estadísticas y obtener recomendaciones personalizadas con IA.
                </p>

                {{-- Google Login Button --}}
                <a href="{{ route('auth.google') }}"
                   class="group relative flex items-center justify-center gap-4 w-full py-4 px-6 rounded-2xl overflow-hidden transition-all duration-300 hover:scale-[1.02] hover:shadow-xl">
                    {{-- Button background --}}
                    <div class="absolute inset-0 bg-white"></div>
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-500/0 via-amber-500/10 to-amber-500/0 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                    {{-- Content --}}
                    <div class="relative flex items-center justify-center gap-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        <span class="text-gray-800 font-semibold text-lg">Continuar con Google</span>
                    </div>
                </a>

                {{-- Divider --}}
                <div class="flex items-center gap-4 my-8">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                    <span class="text-gray-500 text-sm">Acceso seguro</span>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                </div>

                {{-- Features --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-3 text-gray-400">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </div>
                        <span class="text-sm">Guarda tus películas favoritas</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-400">
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-sm">Recomendaciones con IA personalizadas</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-400">
                        <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="text-sm">Estadísticas de tus gustos</span>
                    </div>
                </div>

                {{-- Terms --}}
                <p class="text-gray-600 text-xs text-center mt-8">
                    Al continuar, aceptas nuestros términos de uso y política de privacidad.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
