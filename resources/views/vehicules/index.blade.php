@extends('layouts.app')

@section('title', 'Véhicules')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-car"></i> Véhicules</h3>
        <a href="{{ route('vehicules.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus"></i> Ajouter un véhicule
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="table-vehicules" class="table table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th>Immatriculation</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Année</th>
                        <th>Kilométrage</th>
                        <th>Statut</th>
                        <th class="no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicules as $vehicule)
                        <tr>
                            <td><a href="{{ route('vehicules.show', $vehicule) }}">{{ $vehicule->immatriculation }}</a></td>
                            <td>{{ $vehicule->marque }}</td>
                            <td>{{ $vehicule->modele }}</td>
                            <td>{{ $vehicule->annee ?? '—' }}</td>
                            <td>{{ number_format($vehicule->kilometrage, 0, ',', ' ') }} km</td>
                            <td>
                                <span class="badge badge-statut-{{ $vehicule->statut }}">
                                    {{ str($vehicule->statut)->replace('_', ' ')->ucfirst() }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('vehicules.edit', $vehicule) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('vehicules.destroy', $vehicule) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer le véhicule {{ $vehicule->immatriculation }} ?');">
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
            window.initDataTable('#table-vehicules', {
                columnDefs: [{ orderable: false, targets: 'no-sort' }],
                order: [[0, 'asc']],
            });
        });
    </script>
@endpush
