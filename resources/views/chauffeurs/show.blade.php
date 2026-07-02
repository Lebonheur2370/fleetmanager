@extends('layouts.app')

@section('title', 'Fiche chauffeur — ' . $chauffeur->nomComplet())

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-id-card"></i> {{ $chauffeur->nomComplet() }}</h3>
        <div>
            <a href="{{ route('chauffeurs.edit', $chauffeur) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-pen"></i> Modifier
            </a>
            <a href="{{ route('chauffeurs.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-2">
                    <div class="text-muted small">Matricule</div>
                    <div class="fw-semibold">{{ $chauffeur->matricule }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">Numéro de permis</div>
                    <div class="fw-semibold">{{ $chauffeur->numero_permis }}</div>
                </div>
                <div class="col-md-2">
                    <div class="text-muted small">Expiration permis</div>
                    <div class="fw-semibold">{{ optional($chauffeur->date_expiration_permis)->format('d/m/Y') ?? '—' }}</div>
                </div>
                <div class="col-md-2">
                    <div class="text-muted small">Téléphone</div>
                    <div class="fw-semibold">{{ $chauffeur->telephone }}</div>
                </div>
                <div class="col-md-3">
                    <div class="text-muted small">Email de connexion</div>
                    <div class="fw-semibold">{{ $chauffeur->user->email ?? '—' }}</div>
                </div>
            </div>
            <div class="mt-3">
                <span class="badge badge-disponibilite-{{ $chauffeur->disponibilite }}">
                    {{ str($chauffeur->disponibilite)->replace('_', ' ')->ucfirst() }}
                </span>
            </div>
        </div>
    </div>

    {{-- Emplacements réservés — alimentés lors des prochains sprints (Epics 3, 5) --}}
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="fa-solid fa-car"></i> Affectation actuelle</h6>
                    <p class="text-muted small mb-0">Disponible à partir du Sprint 2 (Epic 3 — Affectations).</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="fa-solid fa-gas-pump"></i> Historique des pleins</h6>
                    <p class="text-muted small mb-0">Disponible à partir du Sprint 4 (Epic 5 — Carburant).</p>
                </div>
            </div>
        </div>
    </div>
@endsection
