@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    {{-- Hero Section --}}
    <section class="mb-16">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-dark-800 to-dark-900 border border-white/5">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 to-cyan-500/10"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>

            <div class="relative z-10 px-8 md:px-12 py-12 md:py-16">
                <div class="text-center max-w-3xl mx-auto">
                    <h1 class="font-display text-5xl md:text-6xl lg:text-7xl tracking-wide mb-4 text-glow-amber">
                        DESCUBRE <span class="text-amber-400">TU PRÓXIMA</span> PELÍCULA
                    </h1>
                    <p class="text-lg text-gray-400 mb-10">
                        Explora miles de películas, obtén recomendaciones personalizadas y guarda tus favoritas.
                    </p>

                    <div class="flex items-center justify-center gap-4">
                        <a href="#trending" class="btn-primary px-8 py-4 rounded-2xl font-semibold text-dark-950 text-lg inline-flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13.5.67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8C20 8.61 17.41 3.8 13.5.67zM11.71 19c-1.78 0-3.22-1.4-3.22-3.14 0-1.62 1.05-2.76 2.81-3.12 1.77-.36 3.6-1.21 4.62-2.58.39 1.29.59 2.65.59 4.04 0 2.65-2.15 4.8-4.8 4.8z"/>
                            </svg>
                            Tendencias
                        </a>
                        <span class="text-gray-600">o</span>
                        <button type="button" id="activate-ai-search" class="px-8 py-4 rounded-2xl font-semibold text-lg inline-flex items-center gap-2 bg-dark-700 border border-cyan-500/30 text-cyan-400 hover:bg-dark-600 hover:border-cyan-500/50 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Búsqueda IA
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('activate-ai-search').addEventListener('click', function() {
            const toggle = document.getElementById('ai-toggle');
            const input = document.getElementById('search-input');
            // Activate AI mode if not already active
            if (!toggle.classList.contains('bg-amber-500')) {
                toggle.click();
            }
            input.focus();
        });
    </script>

    {{-- Trending Section --}}
    <section id="trending" class="mb-16">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13.5.67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8C20 8.61 17.41 3.8 13.5.67zM11.71 19c-1.78 0-3.22-1.4-3.22-3.14 0-1.62 1.05-2.76 2.81-3.12 1.77-.36 3.6-1.21 4.62-2.58.39 1.29.59 2.65.59 4.04 0 2.65-2.15 4.8-4.8 4.8z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-display text-3xl tracking-wide">TENDENCIA ESTA SEMANA</h2>
                <p class="text-gray-500">Las películas más populares del momento</p>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5 stagger-children">
            @foreach(array_slice($trending, 0, 12) as $movie)
                @include('components.movie-card', [
                    'movie' => $movie,
                    'isFavorite' => in_array($movie['id'], $favoriteIds)
                ])
            @endforeach
        </div>
    </section>

    {{-- Popular Section --}}
    <section>
        <div class="flex items-center gap-4 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-yellow-500 flex items-center justify-center">
                <svg class="w-6 h-6 text-dark-950" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-display text-3xl tracking-wide">PELÍCULAS POPULARES</h2>
                <p class="text-gray-500">Las más valoradas por la audiencia</p>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5 stagger-children">
            @foreach($popular as $movie)
                @include('components.movie-card', [
                    'movie' => $movie,
                    'isFavorite' => in_array($movie['id'], $favoriteIds)
                ])
            @endforeach
        </div>
    </section>
@endsection
