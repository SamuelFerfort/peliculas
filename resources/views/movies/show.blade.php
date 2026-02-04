@extends('layouts.app')

@section('title', $movie['title'] ?? 'Detalle')

@section('content')
    <div class="max-w-6xl mx-auto">
        {{-- Hero Section --}}
        <div class="relative rounded-3xl overflow-hidden mb-12">
            {{-- Backdrop --}}
            @if(!empty($movie['backdrop_path']))
                <div class="absolute inset-0">
                    <img src="https://image.tmdb.org/t/p/original{{ $movie['backdrop_path'] }}"
                         alt="{{ $movie['title'] }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-dark-950 via-dark-950/95 to-dark-950/70"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-dark-950 via-transparent to-dark-950/50"></div>
                </div>
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-dark-800 to-dark-900"></div>
            @endif

            <div class="relative z-10 flex flex-col md:flex-row gap-10 p-10">
                {{-- Poster --}}
                <div class="flex-shrink-0">
                    @if(!empty($movie['poster_path']))
                        <div class="relative group">
                            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                 alt="{{ $movie['title'] }}"
                                 class="w-72 rounded-2xl shadow-2xl ring-1 ring-white/10">
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-t from-dark-950/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                    @else
                        <div class="w-72 h-[432px] bg-dark-700 rounded-2xl flex items-center justify-center ring-1 ring-white/10">
                            <svg class="w-20 h-20 text-dark-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1">
                    <h1 class="font-display text-5xl md:text-6xl tracking-wide mb-3">{{ strtoupper($movie['title']) }}</h1>

                    @if(!empty($movie['tagline']))
                        <p class="text-xl text-amber-400/80 italic mb-6">"{{ $movie['tagline'] }}"</p>
                    @endif

                    {{-- Meta badges --}}
                    <div class="flex flex-wrap gap-3 mb-8">
                        @if(!empty($movie['release_date']))
                            <span class="glass px-4 py-2 rounded-xl text-sm flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($movie['release_date'])->format('d M Y') }}
                            </span>
                        @endif

                        @if(!empty($movie['runtime']))
                            <span class="glass px-4 py-2 rounded-xl text-sm flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ floor($movie['runtime'] / 60) }}h {{ $movie['runtime'] % 60 }}min
                            </span>
                        @endif

                        @if(!empty($movie['vote_average']))
                            <span class="rating-badge px-4 py-2 rounded-xl text-sm font-bold text-dark-950 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                                {{ number_format($movie['vote_average'], 1) }}/10
                            </span>
                        @endif
                    </div>

                    {{-- Genres --}}
                    @if(!empty($movie['genres']))
                        <div class="flex flex-wrap gap-2 mb-8">
                            @foreach($movie['genres'] as $genre)
                                <span class="px-4 py-1.5 rounded-full text-sm border border-amber-500/30 text-amber-400 bg-amber-500/10">
                                    {{ $genre['name'] }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Overview --}}
                    @if(!empty($movie['overview']))
                        <div class="mb-8">
                            <h3 class="font-display text-xl tracking-wide text-amber-400 mb-3">SINOPSIS</h3>
                            <p class="text-gray-300 leading-relaxed text-lg">{{ $movie['overview'] }}</p>
                        </div>
                    @endif

                    {{-- Favorite Button --}}
                    <div class="flex items-center gap-4">
                        @auth
                            @if($isFavorite)
                                <form action="{{ route('favorites.destroy', $movie['id']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-8 py-4 rounded-2xl font-semibold bg-red-500 hover:bg-red-600 text-white transition-all inline-flex items-center gap-3 shadow-lg hover:shadow-red-500/25">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                        Eliminar de favoritos
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('favorites.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="tmdb_id" value="{{ $movie['id'] }}">
                                    <input type="hidden" name="title" value="{{ $movie['title'] }}">
                                    <input type="hidden" name="poster_path" value="{{ $movie['poster_path'] ?? '' }}">
                                    <input type="hidden" name="overview" value="{{ $movie['overview'] ?? '' }}">
                                    <input type="hidden" name="release_date" value="{{ $movie['release_date'] ?? '' }}">
                                    <input type="hidden" name="vote_average" value="{{ $movie['vote_average'] ?? '' }}">
                                    <button type="submit" class="btn-primary px-8 py-4 rounded-2xl font-semibold text-dark-950 inline-flex items-center gap-3 shadow-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                        Añadir a favoritos
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-primary px-8 py-4 rounded-2xl font-semibold text-dark-950 inline-flex items-center gap-3 shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                Inicia sesión para guardar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        {{-- AI Analysis --}}
        @if(!empty($movie['overview']))
            <div id="ai-analysis-container" class="relative mb-12 rounded-3xl overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 via-purple-500/20 to-pink-500/20"></div>
                <div class="absolute inset-0 glass"></div>
                <div class="relative z-10 p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl ai-badge flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-2xl tracking-wide">ANÁLISIS IA</h3>
                    </div>
                    <div id="ai-analysis-content" class="text-gray-300 leading-relaxed text-lg prose prose-invert max-w-none">
                        <div class="flex items-center gap-3">
                            <div class="w-5 h-5 border-2 border-cyan-400 border-t-transparent rounded-full animate-spin"></div>
                            <span class="text-cyan-400">Analizando con IA...</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- AI Similar Movies --}}
        @if(!empty($movie['overview']))
            <section id="similar-section" class="mb-12">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl ai-badge flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-display text-2xl tracking-wide">PELÍCULAS SIMILARES</h3>
                        <p class="text-sm text-gray-500">Recomendadas por IA basándose en el contenido</p>
                    </div>
                </div>
                <div id="similar-status" class="flex items-center gap-3 text-cyan-400 mb-6 ml-13">
                    <div class="w-5 h-5 border-2 border-cyan-400 border-t-transparent rounded-full animate-spin"></div>
                    <span>Buscando películas similares con IA...</span>
                </div>
                <div id="similar-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5">
                </div>
            </section>
        @endif

        {{-- Cast --}}
        @if(!empty($movie['credits']['cast']))
            <section class="mb-12">
                <h3 class="font-display text-2xl tracking-wide text-amber-400 mb-6">REPARTO</h3>
                <div class="flex gap-5 overflow-x-auto pb-4 -mx-2 px-2">
                    @foreach(array_slice($movie['credits']['cast'], 0, 10) as $actor)
                        <div class="flex-shrink-0 w-28 text-center group">
                            @if(!empty($actor['profile_path']))
                                <img src="https://image.tmdb.org/t/p/w185{{ $actor['profile_path'] }}"
                                     alt="{{ $actor['name'] }}"
                                     class="w-20 h-20 rounded-2xl mx-auto object-cover mb-3 ring-2 ring-white/10 group-hover:ring-amber-500/50 transition-all">
                            @else
                                <div class="w-20 h-20 rounded-2xl mx-auto bg-dark-700 flex items-center justify-center mb-3 ring-2 ring-white/10">
                                    <svg class="w-8 h-8 text-dark-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </div>
                            @endif
                            <p class="font-medium text-sm truncate text-white">{{ $actor['name'] }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $actor['character'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Trailer --}}
        @if(!empty($movie['videos']['results']))
            @php
                $trailer = collect($movie['videos']['results'])->firstWhere('type', 'Trailer');
            @endphp
            @if($trailer)
                <section class="mb-12">
                    <h3 class="font-display text-2xl tracking-wide text-amber-400 mb-6">TRÁILER</h3>
                    <div class="aspect-video rounded-3xl overflow-hidden ring-1 ring-white/10">
                        <iframe src="https://www.youtube.com/embed/{{ $trailer['key'] }}"
                                class="w-full h-full"
                                allowfullscreen></iframe>
                    </div>
                </section>
            @endif
        @endif

    </div>

    @if(!empty($movie['overview']))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // AI Analysis streaming
            const container = document.getElementById('ai-analysis-content');
            let fullText = '';

            const analysisSource = new EventSource('{{ route('movies.analysis.stream', $movie['id']) }}');

            analysisSource.onmessage = function(event) {
                if (event.data === '[DONE]') {
                    analysisSource.close();
                    return;
                }

                try {
                    const data = JSON.parse(event.data);
                    if (data.content) {
                        fullText += data.content;
                        container.innerHTML = fullText;
                    }
                } catch (e) {}
            };

            analysisSource.onerror = function() {
                analysisSource.close();
                if (!fullText) {
                    document.getElementById('ai-analysis-container').style.display = 'none';
                }
            };

            // AI Similar Movies streaming
            const similarGrid = document.getElementById('similar-grid');
            const similarStatus = document.getElementById('similar-status');
            let hasSimilar = false;

            const similarSource = new EventSource('{{ route('movies.similar.stream', $movie['id']) }}');

            similarSource.onmessage = function(event) {
                if (event.data === '[DONE]') {
                    similarSource.close();
                    similarStatus.style.display = 'none';
                    if (!hasSimilar) {
                        document.getElementById('similar-section').style.display = 'none';
                    }
                    return;
                }

                try {
                    const data = JSON.parse(event.data);
                    if (data.movie) {
                        hasSimilar = true;
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
                            ${movie.isFavorite ? `<div class="absolute top-2 right-2"><span class="w-8 h-8 rounded-lg bg-red-500 flex items-center justify-center"><svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg></span></div>` : ''}
                        `;
                        similarGrid.appendChild(card);
                    }
                } catch (e) {}
            };

            similarSource.onerror = function() {
                similarSource.close();
                similarStatus.style.display = 'none';
                if (!hasSimilar) {
                    document.getElementById('similar-section').style.display = 'none';
                }
            };
        });
    </script>
    @endif
@endsection
