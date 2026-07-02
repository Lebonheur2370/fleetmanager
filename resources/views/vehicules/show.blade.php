@extends('layouts.app')

@section('title', 'Fiche véhicule — ' . $vehicule->immatriculation)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-car"></i> {{ $vehicule->immatriculation }}</h3>
        <div>
            <a href="{{ route('vehicules.edit', $vehicule) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-pen"></i> Modifier
            </a>
            <a href="{{ route('vehicules.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="text-muted small">Marque / Modèle</div>
                    <div class="fw-semibold">{{ $vehicule->marque }} {{ $vehicule->modele }}</div>
                </div>
                <div class="col-md-2">
                    <div class="text-muted small">Année</div>
                    <div class="fw-semibold">{{ $vehicule->annee ?? '—' }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">Kilométrage</div>
                    <div class="fw-semibold">{{ number_format($vehicule->kilometrage, 0, ',', ' ') }} km</div>
                </div>
                <div class="col-md-2">
                    <div class="text-muted small">Statut</div>
                    <span class="badge badge-statut-{{ $vehicule->statut }}">
                        {{ str($vehicule->statut)->replace('_', ' ')->ucfirst() }}
                    </span>
                </div>
                <div class="col-md-2">
                    <div class="text-muted small">Ajouté le</div>
                    <div class="fw-semibold">{{ $vehicule->created_at->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Emplacements réservés — alimentés lors des prochains sprints (Epics 3, 4, 5) --}}
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="fa-solid fa-user"></i> Affectation actuelle</h6>
                    @if($vehicule->affectationActive)
                        <p class="mb-1">
                            <a href="{{ route('chauffeurs.show', $vehicule->affectationActive->chauffeur) }}">
                                {{ $vehicule->affectationActive->chauffeur->nomComplet() }}
                            </a>
                        </p>
                        <p class="text-muted small mb-2">Depuis le {{ $vehicule->affectationActive->date_debut->format('d/m/Y') }}</p>
                        <a href="{{ route('affectations.show', $vehicule->affectationActive) }}" class="btn btn-sm btn-outline-secondary">
                            Voir l'affectation
                        </a>
                    @else
                        <p class="text-muted small mb-0">Aucun chauffeur affecté actuellement.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title d-flex justify-content-between align-items-center">
                        <span><i class="fa-solid fa-screwdriver-wrench"></i> Entretiens</span>
                        <a href="{{ route('entretiens.create') }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-plus"></i></a>
                    </h6>
                    @forelse($vehicule->entretiensRecents as $entretien)
                        <div class="d-flex justify-content-between small mb-1">
                            <a href="{{ route('entretiens.show', $entretien) }}">{{ config("entretiens.types.{$entretien->type}.label", $entretien->type) }}</a>
                            <span class="text-muted">{{ $entretien->date_entretien->format('d/m/Y') }}</span>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Aucun entretien enregistré.</p>
                    @endforelse
                    @if($vehicule->entretiens_count > 5)
                        <a href="{{ route('entretiens.index') }}" class="small">Voir les {{ $vehicule->entretiens_count }} entretiens…</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="fa-solid fa-gas-pump"></i> Carburant</h6>
                    <p class="text-muted small mb-0">Disponible.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
