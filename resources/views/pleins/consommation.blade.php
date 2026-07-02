@extends('layouts.app')

@section('title', 'Consommation moyenne')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-chart-line"></i> Consommation moyenne par véhicule</h3>
        <a href="{{ route('pleins.index') }}" class="btn btn-outline-secondary">Retour aux pleins</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="table-consommation" class="table table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Nombre de pleins</th>
                        <th>Consommation moyenne</th>
                        <th>Dépenses cumulées</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicules as $ligne)
                        <tr>
                            <td>
                                <a href="{{ route('vehicules.show', $ligne['vehicule']) }}">
                                    {{ $ligne['vehicule']->immatriculation }} — {{ $ligne['vehicule']->marque }} {{ $ligne['vehicule']->modele }}
                                </a>
                            </td>
                            <td>{{ $ligne['vehicule']->pleins_count }}</td>
                            <td>
                                @if($ligne['consommation'] !== null)
                                    {{ number_format($ligne['consommation'], 2, ',', ' ') }} L/100km
                                @else
                                    <span class="text-muted small">Pas assez de données (min. 2 pleins)</span>
                                @endif
                            </td>
                            <td>{{ number_format($ligne['depenses'], 0, ',', ' ') }} FCFA</td>
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
            window.initDataTable('#table-consommation');
        });
    </script>
@endpush
