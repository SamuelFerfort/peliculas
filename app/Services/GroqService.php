<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';
    protected string $model = 'openai/gpt-oss-120b';

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
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

        $response = $this->chat($prompt);

        if (!$response) {
            return null;
        }

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

    public function searchMovies(string $query): ?array
    {
        $prompt = "The user is looking for movies with this description: \"{$query}\"

        Suggest exactly 8 movies that match this description.
        IMPORTANT: Return ONLY a JSON array of movie titles in their original language (usually English).
        Example format: [\"Movie Title 1\", \"Movie Title 2\", \"Movie Title 3\"]
        Do not include any explanation, just the JSON array.";

        $response = $this->chat($prompt);

        if (!$response) {
            return null;
        }

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

    public function getSimilarMovies(string $movieTitle, string $overview, array $genres = []): ?array
    {
        $genreText = !empty($genres) ? implode(', ', $genres) : 'unknown';

        $prompt = "Based on the movie '{$movieTitle}' with genres [{$genreText}] and this synopsis: {$overview}

        Suggest exactly 6 similar movies that fans of this movie would enjoy.
        Consider themes, tone, style, and emotional impact.
        IMPORTANT: Return ONLY a JSON array of movie titles in their original language (usually English).
        Do NOT include '{$movieTitle}' itself.
        Example format: [\"Movie Title 1\", \"Movie Title 2\", \"Movie Title 3\"]
        Do not include any explanation, just the JSON array.";

        $response = $this->chat($prompt);

        if (!$response) {
            return null;
        }

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

        return $this->chat($prompt);
    }

    protected function chat(string $prompt): ?string
    {
        try {
            $response = Http::withoutVerifying()
                ->timeout(30)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl, [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 1024,
                ]);

            $data = $response->json();

            if (isset($data['error'])) {
                Log::error('Groq API Error: ' . json_encode($data['error']));
                return null;
            }

            return $data['choices'][0]['message']['content'] ?? null;
        } catch (\Exception $e) {
            Log::error('Groq Exception: ' . $e->getMessage());
            return null;
        }
    }

    public function streamMovieAnalysis(string $movieTitle, string $overview, callable $callback): void
    {
        $prompt = "Analiza brevemente la película '{$movieTitle}' con esta sinopsis: {$overview}.
        Incluye: género principal, tono de la película, y a quién podría gustarle.
        Responde en español en máximo 3 frases.";

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $this->baseUrl,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
                'Accept: text/event-stream',
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'model' => $this->model,
                'messages' => [['role' => 'user', 'content' => $prompt]],
                'temperature' => 0.7,
                'max_tokens' => 1024,
                'stream' => true,
            ]),
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_WRITEFUNCTION => function ($ch, $data) use ($callback) {
                $lines = explode("\n", $data);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (str_starts_with($line, 'data: ')) {
                        $json = substr($line, 6);
                        if ($json === '[DONE]') {
                            $callback(null, true);
                            return strlen($data);
                        }
                        $decoded = json_decode($json, true);
                        $content = $decoded['choices'][0]['delta']['content'] ?? '';
                        if ($content) {
                            $callback($content, false);
                        }
                    }
                }
                return strlen($data);
            },
        ]);

        curl_exec($ch);
        curl_close($ch);
    }
}
