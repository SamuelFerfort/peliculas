@extends('layouts.app')

@section('title', 'Estadísticas')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center gap-4 mb-12">
        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <div>
            <h1 class="font-display text-4xl tracking-wide">MIS ESTADÍSTICAS</h1>
            <p class="text-gray-500">Analiza tus gustos cinematográficos</p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <div class="relative rounded-3xl overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/20 to-orange-500/20"></div>
            <div class="absolute inset-0 glass"></div>
            <div class="relative z-10 p-8 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-500 mx-auto flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-dark-950" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </div>
                <div class="font-display text-6xl text-amber-400 mb-2" id="totalFavorites">-</div>
                <div class="text-gray-400 text-lg">Películas Favoritas</div>
            </div>
        </div>

        <div class="relative rounded-3xl overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 to-blue-500/20"></div>
            <div class="absolute inset-0 glass"></div>
            <div class="relative z-10 p-8 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-500 mx-auto flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8 text-dark-950" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </div>
                <div class="font-display text-6xl text-cyan-400 mb-2" id="averageRating">-</div>
                <div class="text-gray-400 text-lg">Puntuación Media</div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="glass rounded-3xl p-8 border border-white/5">
            <h2 class="font-display text-xl tracking-wide text-amber-400 mb-6">DISTRIBUCIÓN POR PUNTUACIÓN</h2>
            <canvas id="ratingChart"></canvas>
        </div>
        <div class="glass rounded-3xl p-8 border border-white/5">
            <h2 class="font-display text-xl tracking-wide text-cyan-400 mb-6">PELÍCULAS POR AÑO</h2>
            <canvas id="yearChart"></canvas>
        </div>
    </div>

    {{-- Back link --}}
    <div class="mt-12 text-center">
        <a href="{{ route('favorites.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-400 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Ver mis favoritos
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const response = await fetch('{{ route("stats.data") }}');
    const data = await response.json();

    document.getElementById('totalFavorites').textContent = data.totalFavorites;
    document.getElementById('averageRating').textContent = data.averageRating;

    // Rating Distribution Chart
    new Chart(document.getElementById('ratingChart'), {
        type: 'bar',
        data: {
            labels: data.ratingDistribution.labels,
            datasets: [{
                label: 'Películas',
                data: data.ratingDistribution.data,
                backgroundColor: 'rgba(237, 137, 54, 0.8)',
                borderColor: 'rgba(237, 137, 54, 1)',
                borderWidth: 0,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#6b7280',
                        stepSize: 1,
                        font: { family: 'Plus Jakarta Sans' }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        color: '#6b7280',
                        font: { family: 'Plus Jakarta Sans' }
                    },
                    grid: { display: false }
                }
            }
        }
    });

    // Movies by Year Chart
    new Chart(document.getElementById('yearChart'), {
        type: 'line',
        data: {
            labels: data.moviesByYear.labels,
            datasets: [{
                label: 'Películas',
                data: data.moviesByYear.data,
                borderColor: 'rgba(6, 182, 212, 1)',
                backgroundColor: 'rgba(6, 182, 212, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: 'rgba(6, 182, 212, 1)',
                pointBorderColor: '#050505',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#6b7280',
                        stepSize: 1,
                        font: { family: 'Plus Jakarta Sans' }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    ticks: {
                        color: '#6b7280',
                        font: { family: 'Plus Jakarta Sans' }
                    },
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endsection
