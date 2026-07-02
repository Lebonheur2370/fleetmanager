<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Nom <span class="text-danger">*</span></label>
        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
               value="{{ old('nom', $chauffeur->nom ?? '') }}" required autofocus>
        @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Prénom <span class="text-danger">*</span></label>
        <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
               value="{{ old('prenom', $chauffeur->prenom ?? '') }}" required>
        @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Numéro de permis <span class="text-danger">*</span></label>
        <input type="text" name="numero_permis" class="form-control @error('numero_permis') is-invalid @enderror"
               value="{{ old('numero_permis', $chauffeur->numero_permis ?? '') }}" required>
        @error('numero_permis') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Date d'expiration du permis</label>
        <input type="date" name="date_expiration_permis" class="form-control @error('date_expiration_permis') is-invalid @enderror"
               value="{{ old('date_expiration_permis', optional($chauffeur->date_expiration_permis ?? null)->format('Y-m-d')) }}">
        @error('date_expiration_permis') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Téléphone <span class="text-danger">*</span></label>
        <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror"
               value="{{ old('telephone', $chauffeur->telephone ?? '') }}" required placeholder="+241 XX XX XX XX">
        @error('telephone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    @isset($chauffeur)
        <div class="col-md-6 mb-3">
            <label class="form-label">Disponibilité <span class="text-danger">*</span></label>
            <select name="disponibilite" class="form-select @error('disponibilite') is-invalid @enderror" required>
                @foreach(['disponible' => 'Disponible', 'indisponible' => 'Indisponible', 'en_conge' => 'En congé'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('disponibilite', $chauffeur->disponibilite) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('disponibilite') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    @else
        <div class="col-md-6 mb-3">
            <div class="alert alert-info small mb-0">
                <i class="fa-solid fa-circle-info"></i>
                Un compte de connexion (email + mot de passe temporaire) sera généré
                automatiquement à la création.
            </div>
        </div>
    @endisset
</div>
