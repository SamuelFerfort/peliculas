@extends('layouts.app')

@section('title', 'Buscar: ' . $query)

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-dark-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <div>
            <h1 class="font-display text-4xl tracking-wide">RESULTADOS DE BÚSQUEDA</h1>
            <p class="text-gray-500">Mostrando resultados para tu búsqueda</p>
        </div>
    </div>

    {{-- Query display --}}
    <div class="relative rounded-2xl overflow-hidden mb-10">
        <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 to-orange-500/10"></div>
        <div class="absolute inset-0 glass"></div>
        <div class="relative z-10 p-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <svg class="w-6 h-6 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="text-lg text-gray-300">"{{ $query }}"</p>
            </div>
            <div class="text-sm text-gray-500">
                Página <span class="text-amber-400 font-semibold">{{ $currentPage }}</span> de <span class="text-amber-400 font-semibold">{{ $totalPages }}</span>
            </div>
        </div>
    </div>

    @if(count($movies) > 0)
        {{-- Results grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5 stagger-children">
            @foreach($movies as $movie)
                @include('components.movie-card', [
                    'movie' => $movie,
                    'isFavorite' => in_array($movie['id'], $favoriteIds)
                ])
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($totalPages > 1)
            <div class="flex justify-center items-center gap-4 mt-12">
                @if($currentPage > 1)
                    <a href="{{ route('movies.search', ['q' => $query, 'page' => $currentPage - 1]) }}"
                       class="group flex items-center gap-2 px-6 py-3 rounded-xl glass border border-white/10 hover:border-amber-500/50 transition-all">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-amber-400 transition-colors transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span class="text-gray-400 group-hover:text-white transition-colors">Anterior</span>
                    </a>
                @endif

                <div class="flex items-center gap-2">
                    @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                        @if($i == $currentPage)
                            <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-dark-950 font-bold">
                                {{ $i }}
                            </span>
                        @else
                            <a href="{{ route('movies.search', ['q' => $query, 'page' => $i]) }}"
                               class="w-10 h-10 rounded-xl glass border border-white/10 flex items-center justify-center text-gray-400 hover:text-white hover:border-amber-500/50 transition-all">
                                {{ $i }}
                            </a>
                        @endif
                    @endfor
                </div>

                @if($currentPage < $totalPages)
                    <a href="{{ route('movies.search', ['q' => $query, 'page' => $currentPage + 1]) }}"
                       class="group flex items-center gap-2 px-6 py-3 rounded-xl glass border border-white/10 hover:border-amber-500/50 transition-all">
                        <span class="text-gray-400 group-hover:text-white transition-colors">Siguiente</span>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-amber-400 transition-colors transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            </div>
        @endif

        {{-- Back link --}}
        <div class="mt-12 text-center">
            <a href="{{ route('movies.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al inicio
            </a>
        </div>
    @else
        {{-- No results state --}}
        <div class="text-center py-20">
            <div class="w-32 h-32 rounded-3xl bg-dark-800 mx-auto flex items-center justify-center mb-8 animate-float">
                <svg class="w-16 h-16 text-dark-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
                </svg>
            </div>
            <h2 class="font-display text-3xl tracking-wide mb-4">SIN RESULTADOS</h2>
            <p class="text-gray-500 text-lg mb-8 max-w-md mx-auto">
                No se encontraron películas con "<span class="text-amber-400">{{ $query }}</span>"
            </p>
            <a href="{{ route('movies.index') }}" class="btn-primary px-8 py-4 rounded-2xl font-semibold text-dark-950 text-lg shadow-lg inline-flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver al inicio
            </a>
        </div>
    @endif
</div>
@endsection
