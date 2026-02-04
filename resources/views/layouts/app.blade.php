<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title', 'Películas')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: {
                            950: '#050505',
                            900: '#0a0a0a',
                            800: '#121212',
                            700: '#1a1a1a',
                            600: '#242424',
                            500: '#2e2e2e',
                        },
                        amber: {
                            400: '#f6ad55',
                            500: '#ed8936',
                            600: '#dd6b20',
                        },
                        cyan: {
                            400: '#22d3ee',
                            500: '#06b6d4',
                        }
                    },
                    fontFamily: {
                        display: ['Bebas Neue', 'sans-serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Film grain overlay */
        .film-grain::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            opacity: 0.03;
            z-index: 1000;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
        }

        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-dark {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Glow effects */
        .glow-amber {
            box-shadow: 0 0 40px rgba(237, 137, 54, 0.15);
        }

        .glow-cyan {
            box-shadow: 0 0 40px rgba(6, 182, 212, 0.15);
        }

        .text-glow-amber {
            text-shadow: 0 0 30px rgba(237, 137, 54, 0.5);
        }

        /* Movie card effects */
        .movie-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .movie-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8);
        }

        .movie-card:hover .movie-overlay {
            opacity: 1;
        }

        .movie-card:hover .movie-poster {
            transform: scale(1.05);
        }

        .movie-poster {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Navbar blur */
        .nav-blur {
            background: rgba(5, 5, 5, 0.8);
            backdrop-filter: blur(20px) saturate(180%);
        }

        /* Dropdown */
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(237, 137, 54, 0.3); }
            50% { box-shadow: 0 0 40px rgba(237, 137, 54, 0.5); }
        }

        .animate-fade-in {
            animation: fadeInUp 0.5s ease forwards;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Stagger children */
        .stagger-children > *:nth-child(1) { animation-delay: 0.05s; }
        .stagger-children > *:nth-child(2) { animation-delay: 0.1s; }
        .stagger-children > *:nth-child(3) { animation-delay: 0.15s; }
        .stagger-children > *:nth-child(4) { animation-delay: 0.2s; }
        .stagger-children > *:nth-child(5) { animation-delay: 0.25s; }
        .stagger-children > *:nth-child(6) { animation-delay: 0.3s; }
        .stagger-children > *:nth-child(7) { animation-delay: 0.35s; }
        .stagger-children > *:nth-child(8) { animation-delay: 0.4s; }
        .stagger-children > *:nth-child(9) { animation-delay: 0.45s; }
        .stagger-children > *:nth-child(10) { animation-delay: 0.5s; }
        .stagger-children > *:nth-child(11) { animation-delay: 0.55s; }
        .stagger-children > *:nth-child(12) { animation-delay: 0.6s; }

        .stagger-children > * {
            opacity: 0;
            animation: fadeInUp 0.5s ease forwards;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0a0a0a;
        }

        ::-webkit-scrollbar-thumb {
            background: #2e2e2e;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #404040;
        }

        /* Prose styles */
        .prose ul { list-style-type: disc; margin-left: 1.5rem; margin-top: 0.5rem; }
        .prose li { margin-bottom: 0.75rem; }
        .prose strong { color: #f6ad55; font-weight: 600; }
        .prose p { margin-bottom: 0.5rem; }

        /* Rating badge gradient */
        .rating-badge {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        }

        /* AI badge */
        .ai-badge {
            background: linear-gradient(135deg, #06b6d4 0%, #8b5cf6 100%);
        }

        /* Search input focus */
        .search-input:focus {
            box-shadow: 0 0 0 2px rgba(237, 137, 54, 0.3);
        }

        /* Button hover effects */
        .btn-primary {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -10px rgba(237, 137, 54, 0.5);
        }

        .btn-ghost {
            position: relative;
            overflow: hidden;
        }

        .btn-ghost::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-ghost:hover::before {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-dark-950 text-white min-h-screen film-grain">
    {{-- Background gradient orbs --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -left-40 w-80 h-80 bg-cyan-500/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 right-1/3 w-72 h-72 bg-amber-500/5 rounded-full blur-3xl"></div>
    </div>

    {{-- Navigation --}}
    <nav class="nav-blur sticky top-0 z-50 border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between h-20">
                {{-- Logo --}}
                <a href="{{ route('movies.index') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-dark-950" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
                        </svg>
                    </div>
                    <span class="font-display text-2xl tracking-wide text-white group-hover:text-amber-400 transition-colors">CINEMAX</span>
                </a>

                {{-- Search --}}
                <form id="search-form" action="{{ route('movies.search') }}" method="GET" class="flex-1 max-w-xl mx-12">
                    <div class="relative flex items-center">
                        <div id="search-icon" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="q" id="search-input"
                               placeholder="Buscar películas..."
                               value="{{ request('q') }}"
                               class="search-input w-full pl-12 pr-24 py-3 rounded-2xl bg-dark-800 text-white placeholder-gray-500 border border-white/5 focus:outline-none focus:border-amber-500/50 transition-all">
                        <button type="button" id="ai-toggle"
                                class="absolute right-2 flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all duration-300 bg-dark-700 text-gray-400 hover:text-amber-400 hover:bg-dark-600"
                                title="Activar búsqueda con IA">
                            <svg id="ai-icon" class="w-4 h-4 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>IA</span>
                        </button>
                    </div>
                </form>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const form = document.getElementById('search-form');
                        const toggle = document.getElementById('ai-toggle');
                        const input = document.getElementById('search-input');
                        const searchIcon = document.getElementById('search-icon');
                        const aiIcon = document.getElementById('ai-icon');
                        let aiMode = false;

                        toggle.addEventListener('click', function() {
                            aiMode = !aiMode;
                            if (aiMode) {
                                form.action = '{{ route('movies.ai-search') }}';
                                toggle.classList.remove('bg-dark-700', 'text-gray-400', 'hover:text-amber-400', 'hover:bg-dark-600');
                                toggle.classList.add('bg-amber-500', 'text-dark-950', 'hover:bg-amber-400');
                                searchIcon.classList.remove('text-gray-500');
                                searchIcon.classList.add('text-amber-400');
                                input.placeholder = 'Describe lo que buscas... ej: thriller psicológico con final inesperado';
                                input.classList.remove('border-white/5', 'focus:border-amber-500/50');
                                input.classList.add('border-amber-500/30', 'focus:border-amber-500');
                            } else {
                                form.action = '{{ route('movies.search') }}';
                                toggle.classList.add('bg-dark-700', 'text-gray-400', 'hover:text-amber-400', 'hover:bg-dark-600');
                                toggle.classList.remove('bg-amber-500', 'text-dark-950', 'hover:bg-amber-400');
                                searchIcon.classList.add('text-gray-500');
                                searchIcon.classList.remove('text-amber-400');
                                input.placeholder = 'Buscar películas...';
                                input.classList.add('border-white/5', 'focus:border-amber-500/50');
                                input.classList.remove('border-amber-500/30', 'focus:border-amber-500');
                            }
                        });
                    });
                </script>

                {{-- Nav links --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('movies.index') }}" class="btn-ghost px-4 py-2 rounded-xl text-gray-300 hover:text-white transition-colors">
                        Inicio
                    </a>
                    @auth
                        <a href="{{ route('favorites.index') }}" class="btn-ghost px-4 py-2 rounded-xl text-gray-300 hover:text-white transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                            @if(auth()->user()->favorites()->count() > 0)
                                <span class="rating-badge text-dark-950 text-xs px-2 py-0.5 rounded-full font-bold">
                                    {{ auth()->user()->favorites()->count() }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('stats.index') }}" class="btn-ghost px-4 py-2 rounded-xl text-gray-300 hover:text-white transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </a>

                        {{-- User Dropdown --}}
                        <div class="relative dropdown ml-2">
                            <button class="flex items-center gap-3 px-3 py-2 rounded-xl glass hover:bg-white/5 transition-all">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="" class="w-9 h-9 rounded-xl object-cover ring-2 ring-amber-500/20" referrerpolicy="no-referrer">
                                @else
                                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-dark-950 font-bold">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-2 w-56 bg-dark-800 rounded-2xl shadow-2xl py-2 z-50 border border-white/10">
                                <div class="px-4 py-3 border-b border-white/5">
                                    <p class="font-medium text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-sm text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="py-2">
                                    <a href="{{ route('favorites.index') }}" class="flex items-center gap-3 px-4 py-2 text-gray-300 hover:text-white hover:bg-white/5 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                        </svg>
                                        Mis Favoritos
                                    </a>
                                    <a href="{{ route('stats.index') }}" class="flex items-center gap-3 px-4 py-2 text-gray-300 hover:text-white hover:bg-white/5 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        Mis Estadísticas
                                    </a>
                                </div>
                                <div class="border-t border-white/5 pt-2">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-red-400 hover:text-red-300 hover:bg-white/5 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary px-5 py-2.5 rounded-xl font-semibold text-dark-950 shadow-lg">
                            Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="fixed top-24 right-6 z-50 animate-fade-in">
            <div class="glass-dark rounded-2xl px-6 py-4 flex items-center gap-3 shadow-2xl border border-green-500/20">
                <div class="w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="text-green-100">{{ session('success') }}</span>
            </div>
        </div>
        <script>setTimeout(() => document.querySelector('.fixed.top-24')?.remove(), 4000);</script>
    @endif

    @if(session('error'))
        <div class="fixed top-24 right-6 z-50 animate-fade-in">
            <div class="glass-dark rounded-2xl px-6 py-4 flex items-center gap-3 shadow-2xl border border-red-500/20">
                <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <span class="text-red-100">{{ session('error') }}</span>
            </div>
        </div>
        <script>setTimeout(() => document.querySelector('.fixed.top-24')?.remove(), 4000);</script>
    @endif

    {{-- Main content --}}
    <main class="relative z-10 max-w-7xl mx-auto px-6 py-12">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="relative z-10 border-t border-white/5 mt-20">
        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-dark-950" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
                        </svg>
                    </div>
                    <span class="font-display text-xl tracking-wide">CINEMAX</span>
                </div>
                <div class="flex items-center gap-6 text-sm text-gray-500">
                    <span>Powered by TMDB & Groq AI</span>
                    <span class="w-1 h-1 rounded-full bg-gray-600"></span>
                    <a href="https://www.themoviedb.org" target="_blank" class="hover:text-amber-400 transition-colors">TMDB</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
