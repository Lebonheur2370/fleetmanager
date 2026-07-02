@extends('layouts.app')

@section('title', 'Kilométrage global')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-road"></i> Kilométrage global du parc</h3>
        <a href="{{ route('rapports.depenses') }}" class="btn btn-outline-secondary">Voir les dépenses</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Kilométrage cumulé du parc</div>
                    <div class="fs-4 fw-bold text-success">{{ number_format($kilometrageTotal, 0, ',', ' ') }} km</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Kilométrage moyen par véhicule</div>
                    <div class="fs-4 fw-bold">{{ number_format($kilometrageMoyen, 0, ',', ' ') }} km</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="table-kilometrage" class="table table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Statut</th>
                        <th>Kilométrage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicules as $vehicule)
                        <tr>
                            <td><a href="{{ route('vehicules.show', $vehicule) }}">{{ $vehicule->immatriculation }} — {{ $vehicule->marque }} {{ $vehicule->modele }}</a></td>
                            <td><span class="badge badge-statut-{{ $vehicule->statut }}">{{ str($vehicule->statut)->replace('_', ' ')->ucfirst() }}</span></td>
                            <td class="fw-semibold">{{ number_format($vehicule->kilometrage, 0, ',', ' ') }} km</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.initDataTable('#table-kilometrage', { order: [[2, 'desc']] });
        });
    </script>
@endpush
