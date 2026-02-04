@props(['movie', 'isFavorite' => false])

<div class="movie-card group relative rounded-2xl overflow-hidden bg-dark-800 border border-white/5">
    <a href="{{ route('movies.show', $movie['id']) }}" class="block">
        <div class="relative aspect-[2/3] overflow-hidden">
            @if(!empty($movie['poster_path']))
                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                     alt="{{ $movie['title'] }}"
                     class="movie-poster w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-dark-700 flex items-center justify-center">
                    <svg class="w-16 h-16 text-dark-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
                    </svg>
                </div>
            @endif

            {{-- Gradient overlay --}}
            <div class="movie-overlay absolute inset-0 bg-gradient-to-t from-dark-950 via-dark-950/60 to-transparent opacity-0 transition-all duration-500">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-14 h-14 rounded-full bg-amber-500 flex items-center justify-center transform scale-75 opacity-0 group-hover:scale-100 group-hover:opacity-100 transition-all duration-300 shadow-lg">
                        <svg class="w-6 h-6 text-dark-950 ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Bottom info --}}
            <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                <h3 class="font-semibold text-lg truncate text-white">{{ $movie['title'] }}</h3>
                @if(!empty($movie['release_date']))
                    <p class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}</p>
                @endif
            </div>
        </div>
    </a>

    {{-- Rating badge --}}
    @if(!empty($movie['vote_average']))
        <div class="absolute top-3 left-3">
            <span class="rating-badge text-dark-950 text-sm font-bold px-2.5 py-1 rounded-lg flex items-center gap-1 shadow-lg">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
                {{ number_format($movie['vote_average'], 1) }}
            </span>
        </div>
    @endif

    {{-- Favorite button --}}
    <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        @if($isFavorite)
            <form action="{{ route('favorites.destroy', $movie['id']) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-10 h-10 rounded-xl bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-all shadow-lg hover:scale-110" title="Eliminar de favoritos">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
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
                <button type="submit" class="w-10 h-10 rounded-xl glass hover:bg-red-500 text-white flex items-center justify-center transition-all shadow-lg hover:scale-110" title="Añadir a favoritos">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </button>
            </form>
        @endif
    </div>
</div>
