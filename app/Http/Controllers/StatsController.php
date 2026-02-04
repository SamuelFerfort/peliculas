<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class StatsController extends Controller
{
    public function index()
    {
        return view('stats.index');
    }

    public function data()
    {
        $favorites = Favorite::where('user_id', Auth::id())->get();

        // Rating distribution (1-10)
        $ratingDistribution = [];
        for ($i = 1; $i <= 10; $i++) {
            $ratingDistribution[$i] = 0;
        }
        foreach ($favorites as $fav) {
            $rating = (int) round($fav->vote_average);
            if ($rating >= 1 && $rating <= 10) {
                $ratingDistribution[$rating]++;
            }
        }

        // Movies by year
        $moviesByYear = $favorites
            ->filter(fn($f) => $f->release_date)
            ->groupBy(fn($f) => $f->release_date->format('Y'))
            ->map(fn($group) => $group->count())
            ->sortKeys()
            ->toArray();

        // Basic stats
        $totalFavorites = $favorites->count();
        $averageRating = $favorites->count() > 0
            ? round($favorites->avg('vote_average'), 1)
            : 0;

        return response()->json([
            'ratingDistribution' => [
                'labels' => array_keys($ratingDistribution),
                'data' => array_values($ratingDistribution),
            ],
            'moviesByYear' => [
                'labels' => array_keys($moviesByYear),
                'data' => array_values($moviesByYear),
            ],
            'totalFavorites' => $totalFavorites,
            'averageRating' => $averageRating,
        ]);
    }
}
