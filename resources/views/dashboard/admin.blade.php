@extends('layouts.app')

@section('title', 'Tableau de bord - Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Tableau de bord</h3>
            <p class="text-muted mb-0 small">Vue d'ensemble du parc automobile — {{ now()->translatedFormat('l d F Y') }}</p>
        </div>
        <a href="{{ route('rapports.depenses') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-file-lines"></i> Reporting complet
        </a>
    </div>

    {{-- Statistiques principales --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(34,197,94,.12); color:#16a34a;">
                    <i class="fa-solid fa-car"></i>
                </div>
                <div class="stat-card-value">{{ $stats['nombreVehicules'] }}</div>
                <div class="stat-card-label">Véhicules — {{ $stats['vehiculesDisponibles'] }} disponible(s)</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(13,110,253,.12); color:#0d6efd;">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <div class="stat-card-value">{{ $stats['nombreChauffeurs'] }}</div>
                <div class="stat-card-label">Chauffeurs — {{ $stats['chauffeursDisponibles'] }} disponible(s)</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(253,126,20,.12); color:#fd7e14;">
                    <i class="fa-solid fa-right-left"></i>
                </div>
                <div class="stat-card-value">{{ $stats['affectationsActives'] }}</div>
                <div class="stat-card-label">Affectations actives</div>
            </div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: rgba(220,53,69,.12); color:#dc3545;">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div class="stat-card-value">{{ number_format($stats['depensesMoisCourant'], 0, ',', ' ') }}</div>
                <div class="stat-card-label">FCFA dépensés ce mois-ci</div>
            </div>
        </div>
    </div>

    {{-- Graphiques --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="dashboard-card">
                <div class="dashboard-card-title"><i class="fa-solid fa-chart-column"></i> Dépenses des 6 derniers mois</div>
                <canvas id="chart-depenses" height="110"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="dashboard-card">
                <div class="dashboard-card-title"><i class="fa-solid fa-chart-pie"></i> Véhicules par statut</div>
                <canvas id="chart-statuts" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Informations importantes --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="dashboard-card">
                <div class="dashboard-card-title"><i class="fa-solid fa-triangle-exclamation"></i> Alertes entretien</div>
                @forelse($alertesEntretien as $alerte)
                    <div class="alert-row">
                        <span class="alert-dot" style="background-color: {{ $alerte['urgent'] ? '#dc3545' : '#fd7e14' }};"></span>
                        <div class="flex-grow-1">
                            <a href="{{ route('vehicules.show', $alerte['vehicule']) }}" class="fw-semibold text-decoration-none">
                                {{ $alerte['vehicule']->immatriculation }}
                            </a>
                            <div class="text-muted" style="font-size:.78rem;">
                                Seuil {{ $alerte['motif'] }} {{ $alerte['urgent'] ? 'dépassé' : 'proche' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Aucune alerte en cours. 👍</p>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="dashboard-card">
                <div class="dashboard-card-title"><i class="fa-solid fa-id-card-clip"></i> Permis bientôt expirés</div>
                @forelse($permisExpirants as $chauffeur)
                    <div class="alert-row">
                        <span class="alert-dot" style="background-color: {{ $chauffeur->date_expiration_permis->isPast() ? '#dc3545' : '#fd7e14' }};"></span>
                        <div class="flex-grow-1">
                            <a href="{{ route('chauffeurs.show', $chauffeur) }}" class="fw-semibold text-decoration-none">
                                {{ $chauffeur->nomComplet() }}
                            </a>
                            <div class="text-muted" style="font-size:.78rem;">
                                Expire le {{ $chauffeur->date_expiration_permis->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">Aucun permis à renouveler prochainement.</p>
                @endforelse
            </div>
        </div>

        <div class="col-lg-4">
            <div class="dashboard-card">
                <div class="dashboard-card-title"><i class="fa-solid fa-ranking-star"></i> Véhicules les plus coûteux</div>
                <canvas id="chart-top-vehicules" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Activité récente --}}
    <div class="dashboard-card">
        <div class="dashboard-card-title"><i class="fa-solid fa-clock-rotate-left"></i> Dernières affectations</div>
        @forelse($dernieresAffectations as $affectation)
            <div class="alert-row">
                <span class="badge badge-affectation-{{ $affectation->statut }}" style="min-width: 5.5rem;">
                    {{ $affectation->statut === 'active' ? 'Active' : 'Terminée' }}
                </span>
                <div class="flex-grow-1">
                    <a href="{{ route('affectations.show', $affectation) }}" class="text-decoration-none">
                        <strong>{{ $affectation->vehicule->immatriculation ?? '—' }}</strong>
                        →
                        {{ $affectation->chauffeur?->nomComplet() ?? '—' }}
                    </a>
                </div>
                <div class="text-muted small">{{ $affectation->date_debut->format('d/m/Y') }}</div>
            </div>
        @empty
            <p class="text-muted small mb-0">Aucune affectation enregistrée pour le moment.</p>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const vertPrimaire = '#22c55e';
            const grille = { color: '#f1f5f9' };

            // --- Dépenses des 6 derniers mois (barres empilées) ---
            new Chart(document.getElementById('chart-depenses'), {
                type: 'bar',
                data: {
                    labels: @json(collect($depensesParMois)->pluck('label')),
                    datasets: [
                        {
                            label: 'Entretiens',
                            data: @json(collect($depensesParMois)->pluck('entretiens')),
                            backgroundColor: '#fd7e14',
                            borderRadius: 4,
                        },
                        {
                            label: 'Carburant',
                            data: @json(collect($depensesParMois)->pluck('carburant')),
                            backgroundColor: vertPrimaire,
                            borderRadius: 4,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                    scales: {
                        x: { stacked: true, grid: { display: false } },
                        y: { stacked: true, grid: grille, ticks: { callback: (v) => v.toLocaleString('fr-FR') } },
                    },
                },
            });

            // --- Répartition des véhicules par statut (doughnut) ---
            new Chart(document.getElementById('chart-statuts'), {
                type: 'doughnut',
                data: {
                    labels: @json(collect($vehiculesParStatut)->pluck('label')),
                    datasets: [{
                        data: @json(collect($vehiculesParStatut)->pluck('total')),
                        backgroundColor: [vertPrimaire, '#0d6efd', '#fd7e14', '#dc3545'],
                        borderWidth: 2,
                        borderColor: '#fff',
                    }],
                },
                options: {
                    responsive: true,
                    cutout: '65%',
                    plugins: { legend: { position: 'bottom' } },
                },
            });

            // --- Top véhicules les plus coûteux (barres horizontales) ---
            new Chart(document.getElementById('chart-top-vehicules'), {
                type: 'bar',
                data: {
                    labels: @json(collect($topVehicules)->pluck('immatriculation')),
                    datasets: [{
                        label: 'Dépenses (FCFA)',
                        data: @json(collect($topVehicules)->pluck('total')),
                        backgroundColor: '#dc3545',
                        borderRadius: 4,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: grille, ticks: { callback: (v) => v.toLocaleString('fr-FR') } },
                        y: { grid: { display: false } },
                    },
                },
            });
        });
    </script>
@endpush
