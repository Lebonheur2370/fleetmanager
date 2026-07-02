@extends('layouts.app')

@section('title', 'Carburant')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-gas-pump"></i> Pleins de carburant</h3>
        <div>
            <a href="{{ route('pleins.consommation') }}" class="btn btn-outline-success">
                <i class="fa-solid fa-chart-line"></i> Consommation moyenne
            </a>
            <a href="{{ route('pleins.create') }}" class="btn btn-success">
                <i class="fa-solid fa-plus"></i> Enregistrer un plein
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="table-pleins" class="table table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Véhicule</th>
                        <th>Chauffeur</th>
                        <th>Litres</th>
                        <th>Montant</th>
                        <th>Kilométrage</th>
                        <th class="no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pleins as $plein)
                        <tr>
                            <td>{{ $plein->date_plein->format('d/m/Y') }}</td>
                            <td><a href="{{ route('vehicules.show', $plein->vehicule) }}">{{ $plein->vehicule->immatriculation }}</a></td>
                            <td>{{ $plein->chauffeur?->nomComplet() ?? '—' }}</td>
                            <td>{{ number_format($plein->litres, 2, ',', ' ') }} L</td>
                            <td>{{ number_format($plein->montant, 0, ',', ' ') }} FCFA</td>
                            <td>{{ number_format($plein->kilometrage, 0, ',', ' ') }} km</td>
                            <td>
                                <a href="{{ route('pleins.show', $plein) }}" class="btn btn-sm btn-outline-secondary" title="Détails">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('pleins.edit', $plein) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('pleins.destroy', $plein) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer ce plein ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
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
            window.initDataTable('#table-pleins', {
                columnDefs: [{ orderable: false, targets: 'no-sort' }],
                order: [[0, 'desc']],
            });
        });
    </script>
@endpush
