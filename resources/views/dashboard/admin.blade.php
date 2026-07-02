@extends('layouts.app')

@section('title', 'Tableau de bord - Admin')

@section('content')
    <h3 class="mb-4">Tableau de bord — Gestionnaire</h3>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Véhicules</div>
                    <div class="fs-4 fw-bold">{{ $nombreVehicules }}</div>
                    <div class="small text-success">{{ $vehiculesDisponibles }} disponible(s)</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Kilométrage cumulé</div>
                    <div class="fs-4 fw-bold">{{ number_format($kilometrageTotal, 0, ',', ' ') }} km</div>
                    <a href="{{ route('rapports.kilometrage') }}" class="small">Voir le détail →</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Dépenses cumulées</div>
                    <div class="fs-4 fw-bold">{{ number_format($depensesTotal, 0, ',', ' ') }} FCFA</div>
                    <a href="{{ route('rapports.depenses') }}" class="small">Voir le détail →</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Affectations actives</div>
                    <div class="fs-4 fw-bold">{{ $affectationsActives }}</div>
                </div>
            </div>
        </div>
    </div>

    <p class="text-muted small">Reporting complet : <a href="{{ route('rapports.depenses') }}">dépenses par véhicule</a> · <a href="{{ route('rapports.kilometrage') }}">kilométrage global</a>.</p>
@endsection
