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
    <div class="app-shell">
            <input type="checkbox" id="sidebar-toggle-state" class="d-none">

            <nav class="sidebar">
                <div class="sidebar-brand">
                    <i class="fa-solid fa-truck-fast"></i>
                    <span>FleetManager</span>
                </div>

                <ul class="nav flex-column sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-gauge-high"></i> <span>Tableau de bord</span>
                        </a>
                    </li>

                    @if(auth()->user()->isAdmin())
                        <li class="sidebar-section">Gestion du parc</li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('vehicules.*')) active @endif" href="{{ route('vehicules.index') }}">
                                <i class="fa-solid fa-car"></i> <span>Véhicules</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('chauffeurs.*')) active @endif" href="{{ route('chauffeurs.index') }}">
                                <i class="fa-solid fa-id-card"></i> <span>Chauffeurs</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('affectations.*')) active @endif" href="{{ route('affectations.index') }}">
                                <i class="fa-solid fa-right-left"></i> <span>Affectations</span>
                            </a>
                        </li>

                        <li class="sidebar-section">Suivi & coûts</li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('entretiens.*')) active @endif" href="{{ route('entretiens.index') }}">
                                <i class="fa-solid fa-screwdriver-wrench"></i> <span>Entretiens</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('pleins.*')) active @endif" href="{{ route('pleins.index') }}">
                                <i class="fa-solid fa-gas-pump"></i> <span>Carburant</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('rapports.*')) active @endif" href="{{ route('rapports.depenses') }}">
                                <i class="fa-solid fa-chart-line"></i> <span>Reporting</span>
                            </a>
                        </li>
                    @else
                        <li class="sidebar-section">Mon espace</li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('chauffeur.affectation')) active @endif" href="{{ route('chauffeur.affectation') }}">
                                <i class="fa-solid fa-car"></i> <span>Mon véhicule</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('chauffeur.pleins')) active @endif" href="{{ route('chauffeur.pleins') }}">
                                <i class="fa-solid fa-gas-pump"></i> <span>Mes pleins</span>
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="sidebar-footer">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-logout w-100">
                            <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </nav>

            <label for="sidebar-toggle-state" class="sidebar-backdrop"></label>

            <div class="main-wrapper">
                <header class="topbar">
                    <label for="sidebar-toggle-state" class="topbar-burger d-lg-none">
                        <i class="fa-solid fa-bars"></i>
                    </label>

                    <div class="topbar-greeting">
                        <span class="d-none d-sm-inline">Bonjour,</span>
                        <strong>{{ explode(' ', auth()->user()->name)[0] }}</strong> 👋
                    </div>

                    <div class="topbar-actions">
                        @if(auth()->user()->isAdmin())
                            <div class="dropdown">
                                <button class="btn btn-icon position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-bell"></i>
                                    @if($alertesEntretienHeader->isNotEmpty())
                                        <span class="notif-dot"></span>
                                    @endif
                                </button>
                                <div class="dropdown-menu dropdown-menu-end notif-dropdown">
                                    <h6 class="dropdown-header">
                                        <i class="fa-solid fa-triangle-exclamation text-warning"></i> Alertes entretien
                                    </h6>
                                    @forelse($alertesEntretienHeader as $alerte)
                                        <a class="dropdown-item" href="{{ route('vehicules.show', $alerte['vehicule']) }}">
                                            <span class="notif-badge {{ $alerte['urgent'] ? 'bg-danger' : 'bg-warning' }}"></span>
                                            <div>
                                                <div class="fw-semibold">{{ $alerte['vehicule']->immatriculation }}</div>
                                                <div class="small text-muted">
                                                    Seuil {{ $alerte['motif'] }} {{ $alerte['urgent'] ? 'dépassé' : 'proche' }}
                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="dropdown-item-text text-muted small">Aucune alerte pour le moment.</div>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <div class="dropdown">
                            <button class="btn btn-user-chip" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-circle-user"></i>
                                <span class="d-none d-md-flex flex-column align-items-start lh-1">
                                    <span class="fw-semibold small">{{ auth()->user()->name }}</span>
                                    <span class="text-muted" style="font-size: .7rem;">
                                        {{ auth()->user()->isAdmin() ? 'Administrateur' : 'Chauffeur' }}
                                    </span>
                                </span>
                                <i class="fa-solid fa-chevron-down small ms-1"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">{{ auth()->user()->email }}</h6>
                                <a class="dropdown-item" href="{{ route('password.change') }}">
                                    <i class="fa-solid fa-key me-2"></i> Changer le mot de passe
                                </a>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="content-area">
                    @if(session('success'))
                        <div class="alert alert-success alert-auto-dismiss d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                        </div>
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
    </div>

    @stack('scripts')
</body>
</html>
