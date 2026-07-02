<div class="mb-3">
    <label class="form-label">Véhicule <span class="text-danger">*</span></label>
    <select name="vehicule_id" class="form-select @error('vehicule_id') is-invalid @enderror" required>
        <option value="">— Sélectionner —</option>
        @foreach($vehicules as $v)
            <option value="{{ $v->id }}" @selected(old('vehicule_id', $plein->vehicule_id ?? '') == $v->id)>
                {{ $v->immatriculation }} — {{ $v->marque }} {{ $v->modele }}
                (actuellement {{ number_format($v->kilometrage, 0, ',', ' ') }} km)
            </option>
        @endforeach
    </select>
    @error('vehicule_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Chauffeur</label>
    <select name="chauffeur_id" class="form-select @error('chauffeur_id') is-invalid @enderror">
        <option value="">— Non renseigné —</option>
        @foreach($chauffeurs as $c)
            <option value="{{ $c->id }}" @selected(old('chauffeur_id', $plein->chauffeur_id ?? '') == $c->id)>
                {{ $c->nomComplet() }}
            </option>
        @endforeach
    </select>
    @error('chauffeur_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Date du plein <span class="text-danger">*</span></label>
        <input type="date" name="date_plein" class="form-control @error('date_plein') is-invalid @enderror"
               value="{{ old('date_plein', isset($plein) ? $plein->date_plein->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        @error('date_plein') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Kilométrage au compteur <span class="text-danger">*</span></label>
        <input type="number" name="kilometrage" class="form-control @error('kilometrage') is-invalid @enderror"
               value="{{ old('kilometrage', $plein->kilometrage ?? '') }}" min="0" required>
        @error('kilometrage') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Volume (litres) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="litres" class="form-control @error('litres') is-invalid @enderror"
               value="{{ old('litres', $plein->litres ?? '') }}" min="0.01" required>
        @error('litres') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Montant (FCFA) <span class="text-danger">*</span></label>
        <input type="number" step="1" name="montant" class="form-control @error('montant') is-invalid @enderror"
               value="{{ old('montant', $plein->montant ?? '') }}" min="0" required>
        @error('montant') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
