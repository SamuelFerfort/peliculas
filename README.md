# CinemaApp

Aplicación web para descubrir películas con recomendaciones personalizadas mediante IA.

## Sobre el proyecto

Proyecto desarrollado para la asignatura DWES (2º DAW) - Unidad 8: Aplicaciones web híbridas.

La idea era crear algo que consumiera APIs externas y experimentar con inteligencia artificial. Elegí hacer una app de películas porque me permitía integrar IA de varias formas distintas: búsqueda en lenguaje natural, análisis automático, recomendaciones...

## Tecnologías

- **Backend**: Laravel 10, PHP 8.3
- **Frontend**: Tailwind CSS, JavaScript
- **BBDD**: MySQL

## APIs externas

### TMDB (The Movie Database)
Para obtener toda la información de películas: trending, populares, búsqueda, detalles, reparto, tráilers...

- Web: https://www.themoviedb.org
- Docs: https://developer.themoviedb.org/docs

### Groq
Servicio de IA con modelos LLM. Lo uso para todas las funciones de inteligencia artificial de la app.

- Web: https://groq.com
- Modelo: `openai/gpt-oss-120b`
- Docs: https://console.groq.com/docs

### Google OAuth
Autenticación de usuarios con cuenta de Google, implementado con Laravel Socialite.

## Librerías

- **Laravel Socialite** - Para el login con Google
- **Chart.js** - Gráficas en las estadísticas

## Funcionalidades

### Películas
- Listado de películas en tendencia y populares
- Búsqueda por título
- Página de detalle con sinopsis, reparto, tráiler, puntuación...

### IA
- **Búsqueda inteligente**: Describes lo que quieres ver ("películas de terror de los 80") y la IA busca
- **Análisis**: Cada película tiene un análisis generado por IA
- **Similares**: La IA sugiere películas parecidas
- **Recomendaciones**: Si tienes favoritos, te recomienda basándose en tus gustos

Las respuestas de IA se muestran en streaming (aparecen palabra a palabra).

### Usuarios
- Login con Google
- Guardar favoritos
- Estadísticas: gráfica de puntuaciones, películas por año...

## Instalación

```bash
# Clonar e instalar dependencias
git clone [repo]
cd pelicules
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar BBDD y migrar
php artisan migrate

# Arrancar
php artisan serve
```

### Variables de entorno

```env
# TMDB
TMDB_API_KEY=

# Groq (IA)
GROQ_API_KEY=

# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

## Estructura

```
app/
├── Http/Controllers/
│   ├── MovieController.php      # Películas
│   ├── FavoriteController.php   # Favoritos + recomendaciones IA
│   ├── StatsController.php      # Estadísticas
│   └── AuthController.php       # Login Google
├── Models/
│   ├── User.php
│   └── Favorite.php
└── Services/
    ├── TmdbService.php          # API TMDB
    └── GroqService.php          # API Groq (IA)

resources/views/
├── layouts/app.blade.php
├── movies/
│   ├── index.blade.php          # Home
│   ├── show.blade.php           # Detalle
│   ├── search.blade.php         # Búsqueda normal
│   └── ai-search.blade.php      # Búsqueda IA
├── favorites/index.blade.php
├── stats/index.blade.php
└── auth/login.blade.php
```

## Despliegue

Desplegado en VPS con Ubuntu + Nginx + PHP-FPM + MySQL.

---

Víctor - IES María Enríquez - 2024/25
