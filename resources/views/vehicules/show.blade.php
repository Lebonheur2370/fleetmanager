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
                    <p class="text-muted small mb-0">Disponible à partir du Sprint 2 (Epic 3 — Affectations).</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="fa-solid fa-screwdriver-wrench"></i> Entretiens</h6>
                    <p class="text-muted small mb-0">Disponible à partir du Sprint 3 (Epic 4 — Entretiens).</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="fa-solid fa-gas-pump"></i> Carburant</h6>
                    <p class="text-muted small mb-0">Disponible à partir du Sprint 4 (Epic 5 — Carburant).</p>
                </div>
            </div>
        </div>
    </div>
@endsection
