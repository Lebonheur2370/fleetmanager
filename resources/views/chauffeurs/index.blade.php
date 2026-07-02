@extends('layouts.app')

@section('title', 'Chauffeurs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0"><i class="fa-solid fa-id-card"></i> Chauffeurs</h3>
        <a href="{{ route('chauffeurs.create') }}" class="btn btn-success">
            <i class="fa-solid fa-plus"></i> Créer un chauffeur
        </a>
    </div>

    @if(session('identifiants'))
        <div class="alert alert-warning">
            <i class="fa-solid fa-key"></i>
            <strong>Identifiants générés</strong> — à communiquer au chauffeur (ce message ne s'affiche qu'une seule fois) :
            <ul class="mb-0 mt-2">
                <li>Email : <code>{{ session('identifiants')['email'] }}</code></li>
                <li>Mot de passe temporaire : <code>{{ session('identifiants')['password'] }}</code></li>
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="table-chauffeurs" class="table table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th>Matricule</th>
                        <th>Nom complet</th>
                        <th>Permis</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Disponibilité</th>
                        <th class="no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chauffeurs as $chauffeur)
                        <tr>
                            <td>{{ $chauffeur->matricule }}</td>
                            <td><a href="{{ route('chauffeurs.show', $chauffeur) }}">{{ $chauffeur->nomComplet() }}</a></td>
                            <td>{{ $chauffeur->numero_permis }}</td>
                            <td>{{ $chauffeur->telephone }}</td>
                            <td>{{ $chauffeur->user->email ?? '—' }}</td>
                            <td>
                                <span class="badge badge-disponibilite-{{ $chauffeur->disponibilite }}">
                                    {{ str($chauffeur->disponibilite)->replace('_', ' ')->ucfirst() }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('chauffeurs.edit', $chauffeur) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('chauffeurs.destroy', $chauffeur) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer le chauffeur {{ $chauffeur->nomComplet() }} ?');">
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
            window.initDataTable('#table-chauffeurs', {
                columnDefs: [{ orderable: false, targets: 'no-sort' }],
                order: [[1, 'asc']],
            });
        });
    </script>
@endpush
