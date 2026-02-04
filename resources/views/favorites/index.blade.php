@extends('layouts.app')

@section('title', 'Mis favoritos')

@section('content')
    <section>
        {{-- Header --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-500 to-pink-500 flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
            </div>
            <div>
                <h1 class="font-display text-4xl tracking-wide">MIS FAVORITOS</h1>
                <p class="text-gray-500">{{ $favorites->count() }} películas guardadas</p>
            </div>
        </div>

        @if($favorites->count() > 0)
            {{-- AI Recommendations --}}
            @if($favorites->count() >= 2)
                <div id="recommendations-container" class="mb-16">
                    <div class="relative rounded-3xl overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 via-purple-500/20 to-pink-500/20"></div>
                        <div class="absolute inset-0 glass"></div>
                        <div class="relative z-10 p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl ai-badge flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="font-display text-2xl tracking-wide">RECOMENDACIONES IA PARA TI</h2>
                                    <p class="text-sm text-gray-400">Basadas en tus películas favoritas</p>
                                </div>
                            </div>
                            <div id="recommendations-content">
                                <div id="recommendations-loader" class="flex items-center gap-3 text-cyan-400">
                                    <div class="w-5 h-5 border-2 border-cyan-400 border-t-transparent rounded-full animate-spin"></div>
                                    <span>La IA está analizando tus gustos...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="glass rounded-3xl p-8 mb-12 text-center border border-white/5">
                    <div class="w-16 h-16 rounded-2xl ai-badge mx-auto flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-gray-400 text-lg">
                        Añade al menos <span class="text-cyan-400 font-semibold">2 películas</span> a favoritos para recibir recomendaciones personalizadas con IA
                    </p>
                </div>
            @endif

            {{-- Favorites Grid --}}
            <div class="flex items-center gap-4 mb-6">
                <h2 class="font-display text-2xl tracking-wide text-amber-400">TUS PELÍCULAS</h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5 stagger-children">
                @foreach($favorites as $favorite)
                    <div class="movie-card group relative rounded-2xl overflow-hidden bg-dark-800 border border-white/5">
                        <a href="{{ route('movies.show', $favorite->tmdb_id) }}" class="block">
                            <div class="relative aspect-[2/3] overflow-hidden">
                                @if($favorite->poster_path)
                                    <img src="https://image.tmdb.org/t/p/w500{{ $favorite->poster_path }}"
                                         alt="{{ $favorite->title }}"
                                         class="movie-poster w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-dark-700 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-dark-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
                                        </svg>
                                    </div>
                                @endif

                                <div class="movie-overlay absolute inset-0 bg-gradient-to-t from-dark-950 via-dark-950/60 to-transparent opacity-0 transition-all duration-500">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-14 h-14 rounded-full bg-amber-500 flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 shadow-lg">
                                            <svg class="w-6 h-6 text-dark-950 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                    <h3 class="font-semibold text-lg truncate text-white">{{ $favorite->title }}</h3>
                                    @if($favorite->release_date)
                                        <p class="text-sm text-gray-400">{{ $favorite->release_date->format('Y') }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>

                        @if($favorite->vote_average)
                            <div class="absolute top-3 left-3">
                                <span class="rating-badge text-dark-950 text-sm font-bold px-2.5 py-1 rounded-lg flex items-center gap-1 shadow-lg">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                    {{ number_format($favorite->vote_average, 1) }}
                                </span>
                            </div>
                        @endif

                        <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <form action="{{ route('favorites.destroy', $favorite->tmdb_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-xl bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-all shadow-lg hover:scale-110" title="Eliminar de favoritos">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty state --}}
            <div class="text-center py-20">
                <div class="w-32 h-32 rounded-3xl bg-dark-800 mx-auto flex items-center justify-center mb-8 animate-float">
                    <svg class="w-16 h-16 text-dark-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
                    </svg>
                </div>
                <h2 class="font-display text-3xl tracking-wide mb-4">NO TIENES FAVORITOS TODAVÍA</h2>
                <p class="text-gray-500 text-lg mb-8 max-w-md mx-auto">
                    Explora películas y añádelas a tus favoritos para verlas aquí
                </p>
                <a href="{{ route('movies.index') }}" class="btn-primary px-8 py-4 rounded-2xl font-semibold text-dark-950 text-lg shadow-lg inline-flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Explorar películas
                </a>
            </div>
        @endif
    </section>

    @if($favorites->count() >= 2)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('recommendations-content');
            const loader = document.getElementById('recommendations-loader');
            let hasMovies = false;
            let grid = null;

            const eventSource = new EventSource('{{ route('favorites.recommendations') }}');

            eventSource.onmessage = function(event) {
                if (event.data === '[DONE]') {
                    eventSource.close();
                    if (loader) loader.style.display = 'none';
                    if (!hasMovies) {
                        document.getElementById('recommendations-container').style.display = 'none';
                    }
                    return;
                }

                try {
                    const data = JSON.parse(event.data);
                    if (data.movie) {
                        if (!hasMovies) {
                            hasMovies = true;
                            if (loader) loader.style.display = 'none';
                            container.innerHTML = '<div id="recommendations-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5"></div>';
                            grid = document.getElementById('recommendations-grid');
                        }
                        const movie = data.movie;
                        const posterUrl = movie.poster_path
                            ? `https://image.tmdb.org/t/p/w500${movie.poster_path}`
                            : null;
                        const year = movie.release_date ? movie.release_date.substring(0, 4) : '';
                        const rating = movie.vote_average ? movie.vote_average.toFixed(1) : '';

                        const card = document.createElement('div');
                        card.className = 'movie-card group relative rounded-2xl overflow-hidden bg-dark-800 border border-white/5 animate-fade-in';
                        card.innerHTML = `
                            <a href="/movie/${movie.id}" class="block">
                                <div class="relative aspect-[2/3] overflow-hidden">
                                    ${posterUrl
                                        ? `<img src="${posterUrl}" alt="${movie.title}" class="movie-poster w-full h-full object-cover">`
                                        : `<div class="w-full h-full bg-dark-700 flex items-center justify-center"><svg class="w-16 h-16 text-dark-500" fill="currentColor" viewBox="0 0 24 24"><path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/></svg></div>`
                                    }
                                    <div class="movie-overlay absolute inset-0 bg-gradient-to-t from-dark-950 via-dark-950/60 to-transparent opacity-0 transition-all duration-500">
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <div class="w-12 h-12 rounded-full bg-amber-500 flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300">
                                                <svg class="w-5 h-5 text-dark-950 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="absolute bottom-0 left-0 right-0 p-3 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                        <h3 class="font-semibold text-sm truncate text-white">${movie.title}</h3>
                                        ${year ? `<p class="text-xs text-gray-400">${year}</p>` : ''}
                                    </div>
                                </div>
                            </a>
                            ${rating ? `<div class="absolute top-2 left-2"><span class="rating-badge text-dark-950 text-xs font-bold px-2 py-1 rounded-lg flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>${rating}</span></div>` : ''}
                        `;
                        grid.appendChild(card);
                    }
                } catch (e) {
                    console.error('Parse error:', e);
                }
            };

            eventSource.onerror = function() {
                eventSource.close();
                if (loader) loader.style.display = 'none';
                if (!hasMovies) {
                    document.getElementById('recommendations-container').style.display = 'none';
                }
            };
        });
    </script>
    @endif
@endsection
