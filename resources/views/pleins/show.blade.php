@extends('layouts.app')

@section('title', 'Détail du plein')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-gas-pump"></i> Détail du plein</h3>
        <div>
            <a href="{{ route('pleins.edit', $plein) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-pen"></i> Modifier
            </a>
            <a href="{{ route('pleins.index') }}" class="btn btn-outline-secondary">Retour à la liste</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-muted small">Véhicule</div>
                    <div class="fw-semibold">
                        <a href="{{ route('vehicules.show', $plein->vehicule) }}">{{ $plein->vehicule->immatriculation }}</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Chauffeur</div>
                    <div class="fw-semibold">{{ $plein->chauffeur?->nomComplet() ?? '—' }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Date</div>
                    <div class="fw-semibold">{{ $plein->date_plein->format('d/m/Y') }}</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Volume</div>
                    <div class="fw-semibold">{{ number_format($plein->litres, 2, ',', ' ') }} L</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Montant</div>
                    <div class="fw-semibold">{{ number_format($plein->montant, 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="col-md-4">
                    <div class="text-muted small">Kilométrage</div>
                    <div class="fw-semibold">{{ number_format($plein->kilometrage, 0, ',', ' ') }} km</div>
                </div>
            </div>
        </div>
    </div>
@endsection
