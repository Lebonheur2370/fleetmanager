@extends('layouts.app')

@section('title', 'Détail entretien')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-screwdriver-wrench"></i> Détail de l'entretien</h3>
        <div>
            <a href="{{ route('entretiens.edit', $entretien) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-pen"></i> Modifier
            </a>
            <a href="{{ route('entretiens.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-muted small">Véhicule</div>
                    <div class="fw-semibold">
                        <a href="{{ route('vehicules.show', $entretien->vehicule) }}">{{ $entretien->vehicule->immatriculation }}</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Type</div>
                    <div class="fw-semibold">{{ config("entretiens.types.{$entretien->type}.label", $entretien->type) }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Date</div>
                    <div class="fw-semibold">{{ $entretien->date_entretien->format('d/m/Y') }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Kilométrage</div>
                    <div class="fw-semibold">{{ number_format($entretien->kilometrage, 0, ',', ' ') }} km</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Coût</div>
                    <div class="fw-semibold">{{ number_format($entretien->cout, 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Description</div>
                    <div class="fw-semibold">{{ $entretien->description ?? '—' }}</div>
                </div>
            </div>

            <hr>
            <h6><i class="fa-solid fa-bell"></i> Seuils d'alerte (US10)</h6>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="text-muted small">Seuil kilométrique</div>
                    <div class="fw-semibold">{{ $entretien->prochain_kilometrage_seuil ? number_format($entretien->prochain_kilometrage_seuil, 0, ',', ' ').' km' : '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Date prévue</div>
                    <div class="fw-semibold">{{ optional($entretien->prochaine_date_prevue)->format('d/m/Y') ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
