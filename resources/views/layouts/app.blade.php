<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion du centre équestre')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}"><img class="navbar-brand-image"     src="{{ asset('images/logo.png') }}"></a>
            <button class="navbar-toggler" type="button" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">☰</span>
            </button>
            <div class="navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Mon Compte</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('chevaux.index') }}">Mes Chevaux</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('landingPage') }}">Evenements</a></li>
                        @if (Auth::user()->type_client === 'Vétérinaire')
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.veterinaire') }}">Tableau de bord Vétérinaire</a></li>
                    @elseif (Auth::user()->type_client === 'Maréchal')
                        <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.marechal') }}">Tableau de bord Maréchal</a></li>
                        @endif
                        @if (Auth::user()->type_client === 'Gérant')
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.gerant') }}">Tableau de bord</a></li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button">
                                Gestion
                                </a>
                                <div class="dropdown-menu " aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('users.index') }}">Gérer les Utilisateurs</a>
                            <a class="dropdown-item" href="{{ route('chevaux.index') }}">Gérer les Chevaux</a>
                            <a class="dropdown-item" href="{{ route('prestations.index') }}">Gérer les Prestations</a>
                            <a class="dropdown-item" href="{{ route('evenements.index') }}">Gérer les Évènements</a>
                                </div>
                            </li>
                            
                            <li class="nav-item"><a class="nav-link" href="{{ route('livre-prestations.index') }}">Livre des Prestations</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('livre-prestations.mass-add') }}">Affecter des prestations</a></li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav ml-auto">
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        @if (Route::has('register'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('home') }}">Mon Compte</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    Déconnexion
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>
    <script>
        // Script pour toggler la navbar
        document.querySelector('.navbar-toggler').addEventListener('click', function() {
            document.getElementById('navbarNav').classList.toggle('show');
        });
    </script>
    <script src="../../js/app.js"></script>
</body>
</html>
