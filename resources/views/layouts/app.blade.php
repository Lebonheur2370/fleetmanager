<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FleetManager')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="d-flex">
        @auth
        <nav class="sidebar p-3" style="width: 250px;">
            <h5 class="text-white mb-4"><i class="fa-solid fa-truck-fast"></i> FleetManager</h5>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Tableau de bord</a></li>

                @if(auth()->user()->isAdmin())
                    <li class="nav-item"><a class="nav-link" href="{{ route('vehicules.index') }}">Véhicules</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('chauffeurs.index') }}">Chauffeurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('affectations.index') }}">Affectations</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('entretiens.index') }}">Entretiens</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('pleins.index') }}">Carburant</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('rapports.depenses') }}">Reporting</a></li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('chauffeur.affectation') }}">Mon véhicule</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('chauffeur.pleins') }}">Mes pleins</a></li>
                @endif
            </ul>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button class="btn btn-outline-light btn-sm w-100">Déconnexion</button>
            </form>
        </nav>
        @endauth

        <main class="flex-grow-1 p-4">
            @if(session('success'))
                <div class="alert alert-success alert-auto-dismiss">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
