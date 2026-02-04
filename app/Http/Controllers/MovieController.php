<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Services\GroqService;
use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function __construct(
        protected TmdbService $tmdb,
        protected GroqService $ai
    ) {}

    public function index()
    {
        $trending = $this->tmdb->getTrendingMovies();
        $popular = $this->tmdb->getPopularMovies();
        $favoriteIds = Auth::check()
            ? Favorite::where('user_id', Auth::id())->pluck('tmdb_id')->toArray()
            : [];

        return view('movies.index', [
            'trending' => $trending['results'] ?? [],
            'popular' => $popular['results'] ?? [],
            'favoriteIds' => $favoriteIds,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $page = $request->input('page', 1);

        if (!$query) {
            return redirect()->route('movies.index');
        }

        $results = $this->tmdb->searchMovies($query, $page);
        $favoriteIds = Auth::check()
            ? Favorite::where('user_id', Auth::id())->pluck('tmdb_id')->toArray()
            : [];

        return view('movies.search', [
            'movies' => $results['results'] ?? [],
            'query' => $query,
            'currentPage' => $results['page'] ?? 1,
            'totalPages' => $results['total_pages'] ?? 1,
            'favoriteIds' => $favoriteIds,
        ]);
    }

    public function aiSearch(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return redirect()->route('movies.index');
        }

        return view('movies.ai-search', [
            'query' => $query,
        ]);
    }

    public function aiSearchStream(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response('', 204);
        }

        $favoriteIds = Auth::check()
            ? Favorite::where('user_id', Auth::id())->pluck('tmdb_id')->toArray()
            : [];

        return response()->stream(function () use ($query, $favoriteIds) {
            $titles = $this->ai->searchMovies($query);

            if (!$titles || empty($titles)) {
                echo "data: " . json_encode(['error' => 'No se encontraron películas']) . "\n\n";
                echo "data: [DONE]\n\n";
                if (ob_get_level() > 0) ob_flush();
                flush();
                return;
            }

            foreach ($titles as $title) {
                $results = $this->tmdb->searchMovies($title, 1);
                if (!empty($results['results'][0])) {
                    $movie = $results['results'][0];
                    $movie['isFavorite'] = in_array($movie['id'], $favoriteIds);
                    echo "data: " . json_encode(['movie' => $movie]) . "\n\n";
                    if (ob_get_level() > 0) ob_flush();
                    flush();
                }
            }

            echo "data: [DONE]\n\n";
            if (ob_get_level() > 0) ob_flush();
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function show(int $id)
    {
        $movie = $this->tmdb->getMovie($id);
        $isFavorite = Auth::check()
            && Favorite::where('user_id', Auth::id())->where('tmdb_id', $id)->exists();

        return view('movies.show', [
            'movie' => $movie,
            'isFavorite' => $isFavorite,
        ]);
    }

    public function analysis(int $id)
    {
        $movie = $this->tmdb->getMovie($id);

        if (empty($movie['title']) || empty($movie['overview'])) {
            return response()->json(['html' => null]);
        }

        $analysis = $this->ai->getMovieAnalysis($movie['title'], $movie['overview']);

        if (!$analysis) {
            return response()->json(['html' => null]);
        }

        return response()->json(['html' => Str::markdown($analysis)]);
    }

    public function similarStream(int $id)
    {
        $movie = $this->tmdb->getMovie($id);

        if (empty($movie['title']) || empty($movie['overview'])) {
            return response('', 204);
        }

        $genres = collect($movie['genres'] ?? [])->pluck('name')->toArray();
        $favoriteIds = Auth::check()
            ? Favorite::where('user_id', Auth::id())->pluck('tmdb_id')->toArray()
            : [];

        return response()->stream(function () use ($movie, $genres, $favoriteIds) {
            $titles = $this->ai->getSimilarMovies($movie['title'], $movie['overview'], $genres);

            if (!$titles || empty($titles)) {
                echo "data: [DONE]\n\n";
                if (ob_get_level() > 0) ob_flush();
                flush();
                return;
            }

            foreach ($titles as $title) {
                $results = $this->tmdb->searchMovies($title, 1);
                if (!empty($results['results'][0])) {
                    $found = $results['results'][0];
                    // Don't show the same movie
                    if ($found['id'] != $movie['id']) {
                        $found['isFavorite'] = in_array($found['id'], $favoriteIds);
                        echo "data: " . json_encode(['movie' => $found]) . "\n\n";
                        if (ob_get_level() > 0) ob_flush();
                        flush();
                    }
                }
            }

            echo "data: [DONE]\n\n";
            if (ob_get_level() > 0) ob_flush();
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function analysisStream(int $id)
    {
        $movie = $this->tmdb->getMovie($id);

        if (empty($movie['title']) || empty($movie['overview'])) {
            return response('', 204);
        }

        return response()->stream(function () use ($movie) {
            $this->ai->streamMovieAnalysis(
                $movie['title'],
                $movie['overview'],
                function ($content, $done) {
                    if ($done) {
                        echo "data: [DONE]\n\n";
                    } else {
                        echo "data: " . json_encode(['content' => $content]) . "\n\n";
                    }
                    if (ob_get_level() > 0) ob_flush();
                    flush();
                }
            );
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
