@extends('layouts.app')

@section('title', 'Entretiens')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-screwdriver-wrench"></i> Entretiens</h3>
        <a href="{{ route('entretiens.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus"></i> Enregistrer un entretien
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="table-entretiens" class="table table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th>Véhicule</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Kilométrage</th>
                        <th>Coût</th>
                        <th>Prochaine échéance</th>
                        <th class="no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entretiens as $entretien)
                        <tr>
                            <td><a href="{{ route('vehicules.show', $entretien->vehicule) }}">{{ $entretien->vehicule->immatriculation ?? '—' }}</a></td>
                            <td>{{ config("entretiens.types.{$entretien->type}.label", $entretien->type) }}</td>
                            <td>{{ $entretien->date_entretien->format('d/m/Y') }}</td>
                            <td>{{ number_format($entretien->kilometrage, 0, ',', ' ') }} km</td>
                            <td>{{ number_format($entretien->cout, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @if($entretien->prochain_kilometrage_seuil)
                                    <div class="small">{{ number_format($entretien->prochain_kilometrage_seuil, 0, ',', ' ') }} km</div>
                                @endif
                                @if($entretien->prochaine_date_prevue)
                                    <div class="small text-muted">{{ $entretien->prochaine_date_prevue->format('d/m/Y') }}</div>
                                @endif
                                @if(!$entretien->prochain_kilometrage_seuil && !$entretien->prochaine_date_prevue)
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('entretiens.show', $entretien) }}" class="btn btn-sm btn-outline-secondary" title="Détails">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('entretiens.edit', $entretien) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('entretiens.destroy', $entretien) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer cet entretien ?');">
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
            window.initDataTable('#table-entretiens', {
                columnDefs: [{ orderable: false, targets: 'no-sort' }],
                order: [[2, 'desc']],
            });
        });
    </script>
@endpush
