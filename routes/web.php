<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

// Películas
Route::get('/', [MovieController::class, 'index'])->name('movies.index');
Route::get('/search', [MovieController::class, 'search'])->name('movies.search');
Route::get('/ai-search', [MovieController::class, 'aiSearch'])->name('movies.ai-search');
Route::get('/ai-search/stream', [MovieController::class, 'aiSearchStream'])->name('movies.ai-search.stream');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/movie/{id}/analysis', [MovieController::class, 'analysis'])->name('movies.analysis');
Route::get('/movie/{id}/analysis/stream', [MovieController::class, 'analysisStream'])->name('movies.analysis.stream');
Route::get('/movie/{id}/similar/stream', [MovieController::class, 'similarStream'])->name('movies.similar.stream');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Favoritos (protegidos)
Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::get('/favorites/recommendations', [FavoriteController::class, 'recommendations'])->name('favorites.recommendations');
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{tmdbId}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    // Estadísticas
    Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');
    Route::get('/stats/data', [StatsController::class, 'data'])->name('stats.data');
});
