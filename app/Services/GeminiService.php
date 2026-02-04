<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    public function getRecommendations(array $favoriteMovies): ?string
    {
        if (empty($favoriteMovies)) {
            return null;
        }

        $movieTitles = collect($favoriteMovies)->pluck('title')->implode(', ');

        $prompt = "Basándote en que al usuario le gustan estas películas: {$movieTitles}.
        Recomienda 5 películas similares que podría disfrutar.
        Para cada película, proporciona el título y una breve explicación de por qué le gustaría.
        Responde en español y en formato de lista simple.";

        return $this->generateContent($prompt);
    }

    public function getRecommendationTitles(array $favoriteMovies): ?array
    {
        if (empty($favoriteMovies)) {
            return null;
        }

        $movieTitles = collect($favoriteMovies)->pluck('title')->implode(', ');

        $prompt = "Based on the user liking these movies: {$movieTitles}.
        Recommend exactly 6 similar movies they would enjoy.
        IMPORTANT: Return ONLY a JSON array of movie titles in their original language (usually English).
        Example format: [\"Movie Title 1\", \"Movie Title 2\", \"Movie Title 3\"]
        Do not include any explanation, just the JSON array.";

        $response = $this->generateContent($prompt);

        if (!$response) {
            return null;
        }

        // Extract JSON array from response
        preg_match('/\[.*\]/s', $response, $matches);

        if (empty($matches)) {
            return null;
        }

        try {
            return json_decode($matches[0], true);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getMovieAnalysis(string $movieTitle, string $overview): ?string
    {
        $prompt = "Analiza brevemente la película '{$movieTitle}' con esta sinopsis: {$overview}.
        Incluye: género principal, tono de la película, y a quién podría gustarle.
        Responde en español en máximo 3 frases.";

        return $this->generateContent($prompt);
    }

    protected function generateContent(string $prompt): ?string
    {
        try {
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->post("{$this->baseUrl}?key={$this->apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]);

            $data = $response->json();

            if (isset($data['error'])) {
                Log::error('Gemini API Error: ' . json_encode($data['error']));
                return null;
            }

            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return null;
        }
    }
}
