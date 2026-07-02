@extends('layouts.app')

@section('title', 'Affectations')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-right-left"></i> Affectations</h3>
        <a href="{{ route('affectations.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus"></i> Nouvelle affectation
        </a>
    </div>

    {{-- US8 — Filtres par véhicule, chauffeur et période --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('affectations.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Véhicule</label>
                    <select name="vehicule_id" class="form-select form-select-sm">
                        <option value="">Tous</option>
                        @foreach($vehicules as $vehicule)
                            <option value="{{ $vehicule->id }}" @selected(request('vehicule_id') == $vehicule->id)>
                                {{ $vehicule->immatriculation }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Chauffeur</label>
                    <select name="chauffeur_id" class="form-select form-select-sm">
                        <option value="">Tous</option>
                        @foreach($chauffeurs as $chauffeur)
                            <option value="{{ $chauffeur->id }}" @selected(request('chauffeur_id') == $chauffeur->id)>
                                {{ $chauffeur->nomComplet() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Du</label>
                    <input type="date" name="date_de" class="form-control form-control-sm" value="{{ request('date_de') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Au</label>
                    <input type="date" name="date_a" class="form-control form-control-sm" value="{{ request('date_a') }}">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill">
                        <i class="fa-solid fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('affectations.index') }}" class="btn btn-sm btn-outline-secondary" title="Réinitialiser">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
            </form>
            <div class="mt-2 text-end">
                <a href="{{ route('affectations.index', array_merge(request()->query(), ['export' => 1])) }}"
                   class="btn btn-sm btn-outline-success">
                    <i class="fa-solid fa-file-csv"></i> Exporter en CSV
                </a>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="table-affectations" class="table table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Chauffeur</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Statut</th>
                        <th class="no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($affectations as $affectation)
                        <tr>
                            <td><a href="{{ route('vehicules.show', $affectation->vehicule) }}">{{ $affectation->vehicule->immatriculation ?? '—' }}</a></td>
                            <td>
                                @if($affectation->chauffeur)
                                    <a href="{{ route('chauffeurs.show', $affectation->chauffeur) }}">{{ $affectation->chauffeur->nomComplet() }}</a>
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $affectation->date_debut->format('d/m/Y H:i') }}</td>
                            <td>{{ $affectation->date_fin?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td>
                                <span class="badge badge-affectation-{{ $affectation->statut }}">
                                    {{ $affectation->statut === 'active' ? 'Active' : 'Terminée' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('affectations.show', $affectation) }}" class="btn btn-sm btn-outline-secondary" title="Détails">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                @if($affectation->statut === 'active')
                                    <form action="{{ route('affectations.cloturer', $affectation) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Clôturer cette affectation ?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Clôturer">
                                            <i class="fa-solid fa-lock"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('affectations.destroy', $affectation) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette affectation ?');">
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
            window.initDataTable('#table-affectations', {
                columnDefs: [{ orderable: false, targets: 'no-sort' }],
                order: [[2, 'desc']],
            });
        });
    </script>
@endpush
