<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TmdbService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.tmdb.api_key');
        $this->baseUrl = config('services.tmdb.base_url');
    }

    public function getPopularMovies(int $page = 1): array
    {
        $response = Http::withoutVerifying()->get("{$this->baseUrl}/movie/popular", [
            'api_key' => $this->apiKey,
            'language' => 'es-ES',
            'page' => $page,
        ]);

        return $response->json() ?? [];
    }

    public function getTrendingMovies(): array
    {
        $response = Http::withoutVerifying()->get("{$this->baseUrl}/trending/movie/week", [
            'api_key' => $this->apiKey,
            'language' => 'es-ES',
        ]);

        return $response->json() ?? [];
    }

    public function searchMovies(string $query, int $page = 1): array
    {
        $response = Http::withoutVerifying()->get("{$this->baseUrl}/search/movie", [
            'api_key' => $this->apiKey,
            'language' => 'es-ES',
            'query' => $query,
            'page' => $page,
        ]);

        return $response->json() ?? [];
    }

    public function getMovie(int $id): array
    {
        $response = Http::withoutVerifying()->get("{$this->baseUrl}/movie/{$id}", [
            'api_key' => $this->apiKey,
            'language' => 'es-ES',
            'append_to_response' => 'credits,videos,recommendations',
        ]);

        return $response->json() ?? [];
    }

    public function getGenres(): array
    {
        $response = Http::withoutVerifying()->get("{$this->baseUrl}/genre/movie/list", [
            'api_key' => $this->apiKey,
            'language' => 'es-ES',
        ]);

        return $response->json()['genres'] ?? [];
    }

    public function getImageUrl(string $path, string $size = 'w500'): string
    {
        return "https://image.tmdb.org/t/p/{$size}{$path}";
    }
}
