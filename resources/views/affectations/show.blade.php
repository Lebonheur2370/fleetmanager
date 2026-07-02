@extends('layouts.app')

@section('title', 'Détail affectation')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-right-left"></i> Détail de l'affectation</h3>
        <a href="{{ route('affectations.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-muted small">Véhicule</div>
                    <div class="fw-semibold fs-5">
                        <a href="{{ route('vehicules.show', $affectation->vehicule) }}">{{ $affectation->vehicule->immatriculation }}</a>
                    </div>
                    <div class="text-muted">{{ $affectation->vehicule->marque }} {{ $affectation->vehicule->modele }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Chauffeur</div>
                    <div class="fw-semibold fs-5">
                        <a href="{{ route('chauffeurs.show', $affectation->chauffeur) }}">{{ $affectation->chauffeur->nomComplet() }}</a>
                    </div>
                    <div class="text-muted">{{ $affectation->chauffeur->matricule }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Statut</div>
                    <span class="badge badge-affectation-{{ $affectation->statut }} fs-6">
                        {{ $affectation->statut === 'active' ? 'Active' : 'Terminée' }}
                    </span>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Date de début</div>
                    <div class="fw-semibold">{{ $affectation->date_debut->format('d/m/Y H:i') }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Date de fin</div>
                    <div class="fw-semibold">{{ $affectation->date_fin?->format('d/m/Y H:i') ?? '—' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Motif</div>
                    <div class="fw-semibold">{{ $affectation->motif ?? '—' }}</div>
                </div>
            </div>

            @if($affectation->statut === 'active')
                <hr>
                <form action="{{ route('affectations.cloturer', $affectation) }}" method="POST"
                      onsubmit="return confirm('Clôturer cette affectation ?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning">
                        <i class="fa-solid fa-lock"></i> Clôturer cette affectation
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
