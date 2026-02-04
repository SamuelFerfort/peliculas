<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Services\GroqService;
use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct(
        protected GroqService $ai,
        protected TmdbService $tmdb
    ) {}

    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('favorites.index', [
            'favorites' => $favorites,
        ]);
    }

    public function recommendations()
    {
        $favorites = Favorite::where('user_id', Auth::id())->get();
        $favoriteIds = $favorites->pluck('tmdb_id')->toArray();

        if ($favorites->count() < 2) {
            return response('', 204);
        }

        return response()->stream(function () use ($favorites, $favoriteIds) {
            $titles = $this->ai->getRecommendationTitles($favorites->toArray());

            if (!$titles || empty($titles)) {
                echo "data: [DONE]\n\n";
                if (ob_get_level() > 0) ob_flush();
                flush();
                return;
            }

            $count = 0;
            foreach ($titles as $title) {
                $results = $this->tmdb->searchMovies($title, 1);
                if (!empty($results['results'][0])) {
                    $movie = $results['results'][0];
                    if (!in_array($movie['id'], $favoriteIds)) {
                        echo "data: " . json_encode(['movie' => $movie]) . "\n\n";
                        if (ob_get_level() > 0) ob_flush();
                        flush();
                        $count++;
                    }
                }
                if ($count >= 6) break;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tmdb_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'poster_path' => 'nullable|string',
            'overview' => 'nullable|string',
            'release_date' => 'nullable|date',
            'vote_average' => 'nullable|numeric',
        ]);

        // Check if already favorited by this user
        $exists = Favorite::where('user_id', Auth::id())
            ->where('tmdb_id', $validated['tmdb_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Esta película ya está en tus favoritos.');
        }

        Favorite::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', '¡Película añadida a favoritos!');
    }

    public function destroy(int $tmdbId)
    {
        Favorite::where('user_id', Auth::id())
            ->where('tmdb_id', $tmdbId)
            ->delete();

        return back()->with('success', 'Película eliminada de favoritos.');
    }
}
