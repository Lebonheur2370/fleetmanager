@extends('layouts.app')

@section('title', 'Nouvelle affectation')

@section('content')
    <h3 class="mb-4"><i class="fa-solid fa-right-left"></i> Affecter un véhicule à un chauffeur</h3>

    <div class="card shadow-sm" style="max-width: 700px;">
        <div class="card-body">
            @if($vehicules->isEmpty() || $chauffeurs->isEmpty())
                <div class="alert alert-warning mb-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    @if($vehicules->isEmpty())
                        Aucun véhicule disponible actuellement.
                    @endif
                    @if($chauffeurs->isEmpty())
                        Aucun chauffeur disponible actuellement.
                    @endif
                    Veuillez clôturer une affectation existante ou ajouter un
                    {{ $vehicules->isEmpty() ? 'véhicule' : 'chauffeur' }} avant de continuer.
                </div>
            @else
                <form method="POST" action="{{ route('affectations.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Véhicule disponible <span class="text-danger">*</span></label>
                        <select name="vehicule_id" class="form-select @error('vehicule_id') is-invalid @enderror" required autofocus>
                            <option value="">— Sélectionner —</option>
                            @foreach($vehicules as $vehicule)
                                <option value="{{ $vehicule->id }}" @selected(old('vehicule_id') == $vehicule->id)>
                                    {{ $vehicule->immatriculation }} — {{ $vehicule->marque }} {{ $vehicule->modele }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicule_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Chauffeur disponible <span class="text-danger">*</span></label>
                        <select name="chauffeur_id" class="form-select @error('chauffeur_id') is-invalid @enderror" required>
                            <option value="">— Sélectionner —</option>
                            @foreach($chauffeurs as $chauffeur)
                                <option value="{{ $chauffeur->id }}" @selected(old('chauffeur_id') == $chauffeur->id)>
                                    {{ $chauffeur->matricule }} — {{ $chauffeur->nomComplet() }}
                                </option>
                            @endforeach
                        </select>
                        @error('chauffeur_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de début <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="date_debut" class="form-control @error('date_debut') is-invalid @enderror"
                                   value="{{ old('date_debut', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('date_debut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date de fin prévue</label>
                            <input type="datetime-local" name="date_fin" class="form-control @error('date_fin') is-invalid @enderror"
                                   value="{{ old('date_fin') }}">
                            @error('date_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Motif</label>
                        <textarea name="motif" class="form-control @error('motif') is-invalid @enderror" rows="2">{{ old('motif') }}</textarea>
                        @error('motif') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-check"></i> Valider l'affectation
                    </button>
                    <a href="{{ route('affectations.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </form>
            @endif
        </div>
    </div>
@endsection
