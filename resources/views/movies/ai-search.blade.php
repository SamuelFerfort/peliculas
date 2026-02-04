@extends('layouts.app')

@section('title', 'Búsqueda IA: ' . $query)

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 rounded-2xl ai-badge flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <h1 class="font-display text-4xl tracking-wide">BÚSQUEDA IA</h1>
            <p class="text-gray-400">Resultados inteligentes para tu consulta</p>
        </div>
    </div>

    {{-- Query display --}}
    <div class="relative rounded-2xl overflow-hidden mb-10">
        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/10 via-purple-500/10 to-pink-500/10"></div>
        <div class="absolute inset-0 glass"></div>
        <div class="relative z-10 p-6 flex items-center gap-4">
            <svg class="w-6 h-6 text-cyan-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            <p class="text-lg text-gray-300 italic">"{{ $query }}"</p>
        </div>
    </div>

    {{-- Status indicator --}}
    <div id="search-status" class="flex items-center gap-3 mb-8">
        <div class="w-5 h-5 border-2 border-cyan-400 border-t-transparent rounded-full animate-spin"></div>
        <span class="text-cyan-400">La IA está buscando películas...</span>
    </div>

    {{-- Results grid --}}
    <div id="results-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5 stagger-children">
    </div>

    {{-- No results state --}}
    <div id="no-results" class="hidden">
        <div class="text-center py-20">
            <div class="w-32 h-32 rounded-3xl bg-dark-800 mx-auto flex items-center justify-center mb-8">
                <svg class="w-16 h-16 text-dark-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
                </svg>
            </div>
            <h2 class="font-display text-3xl tracking-wide mb-4">SIN RESULTADOS</h2>
            <p class="text-gray-500 text-lg mb-8 max-w-md mx-auto">
                No se encontraron películas para "<span class="text-cyan-400">{{ $query }}</span>"
            </p>
            <a href="{{ route('movies.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al inicio
            </a>
        </div>
    </div>

    {{-- Back link --}}
    <div id="back-link" class="mt-12 text-center hidden">
        <a href="{{ route('movies.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver al inicio
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('results-grid');
    const status = document.getElementById('search-status');
    const noResults = document.getElementById('no-results');
    const backLink = document.getElementById('back-link');
    let hasMovies = false;

    const eventSource = new EventSource('{{ route('movies.ai-search.stream') }}?q={{ urlencode($query) }}');

    eventSource.onmessage = function(event) {
        if (event.data === '[DONE]') {
            eventSource.close();
            status.style.display = 'none';
            if (!hasMovies) {
                noResults.classList.remove('hidden');
            } else {
                backLink.classList.remove('hidden');
            }
            return;
        }

        try {
            const data = JSON.parse(event.data);

            if (data.error) {
                status.innerHTML = `<span class="text-red-400">${data.error}</span>`;
                return;
            }

            if (data.movie) {
                hasMovies = true;
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
                                    <div class="w-14 h-14 rounded-full bg-amber-500 flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 shadow-lg">
                                        <svg class="w-6 h-6 text-dark-950 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                <h3 class="font-semibold text-lg truncate text-white">${movie.title}</h3>
                                ${year ? `<p class="text-sm text-gray-400">${year}</p>` : ''}
                            </div>
                        </div>
                    </a>
                    ${rating ? `<div class="absolute top-3 left-3"><span class="rating-badge text-dark-950 text-sm font-bold px-2.5 py-1 rounded-lg flex items-center gap-1 shadow-lg"><svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>${rating}</span></div>` : ''}
                    ${movie.isFavorite ? `<div class="absolute top-3 right-3"><span class="w-8 h-8 rounded-lg bg-red-500 flex items-center justify-center"><svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg></span></div>` : ''}
                `;
                grid.appendChild(card);
            }
        } catch (e) {
            console.error('Parse error:', e);
        }
    };

    eventSource.onerror = function() {
        eventSource.close();
        status.style.display = 'none';
        if (!hasMovies) {
            noResults.classList.remove('hidden');
        } else {
            backLink.classList.remove('hidden');
        }
    };
});
</script>
@endsection
