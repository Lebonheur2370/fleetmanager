<div class="mb-3">
    <label class="form-label">Immatriculation <span class="text-danger">*</span></label>
    <input type="text" name="immatriculation" class="form-control @error('immatriculation') is-invalid @enderror"
           value="{{ old('immatriculation', $vehicule->immatriculation ?? '') }}" required autofocus>
    @error('immatriculation') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Marque <span class="text-danger">*</span></label>
        <input type="text" name="marque" class="form-control @error('marque') is-invalid @enderror"
               value="{{ old('marque', $vehicule->marque ?? '') }}" required>
        @error('marque') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Modèle <span class="text-danger">*</span></label>
        <input type="text" name="modele" class="form-control @error('modele') is-invalid @enderror"
               value="{{ old('modele', $vehicule->modele ?? '') }}" required>
        @error('modele') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Année</label>
        <input type="number" name="annee" class="form-control @error('annee') is-invalid @enderror"
               value="{{ old('annee', $vehicule->annee ?? '') }}" min="1980" max="{{ date('Y') + 1 }}">
        @error('annee') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Kilométrage <span class="text-danger">*</span></label>
        <input type="number" name="kilometrage" class="form-control @error('kilometrage') is-invalid @enderror"
               value="{{ old('kilometrage', $vehicule->kilometrage ?? 0) }}" min="0" required>
        @error('kilometrage') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    @isset($vehicule)
        <div class="col-md-4 mb-3">
            <label class="form-label">Statut <span class="text-danger">*</span></label>
            <select name="statut" class="form-select @error('statut') is-invalid @enderror" required>
                @foreach(['disponible' => 'Disponible', 'affecte' => 'Affecté', 'en_entretien' => 'En entretien', 'hors_service' => 'Hors service'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('statut', $vehicule->statut) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @endisset
</div>
