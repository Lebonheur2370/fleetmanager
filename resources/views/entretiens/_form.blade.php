<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Véhicule <span class="text-danger">*</span></label>
        <select name="vehicule_id" id="vehicule_id" class="form-select @error('vehicule_id') is-invalid @enderror" required autofocus>
            <option value="">— Sélectionner —</option>
            @foreach($vehicules as $vehicule)
                <option value="{{ $vehicule->id }}"
                        data-kilometrage="{{ $vehicule->kilometrage }}"
                        @selected(old('vehicule_id', $entretien->vehicule_id ?? null) == $vehicule->id)>
                    {{ $vehicule->immatriculation }} — {{ $vehicule->marque }} {{ $vehicule->modele }}
                    ({{ number_format($vehicule->kilometrage, 0, ',', ' ') }} km)
                </option>
            @endforeach
        </select>
        @error('vehicule_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Type d'entretien <span class="text-danger">*</span></label>
        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="">— Sélectionner —</option>
            @foreach($types as $value => $config)
                <option value="{{ $value }}"
                        data-intervalle-km="{{ $config['intervalle_km'] }}"
                        data-intervalle-jours="{{ $config['intervalle_jours'] }}"
                        @selected(old('type', $entretien->type ?? null) === $value)>
                    {{ $config['label'] }}
                </option>
            @endforeach
        </select>
        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Date de l'entretien <span class="text-danger">*</span></label>
        <input type="date" name="date_entretien" id="date_entretien" class="form-control @error('date_entretien') is-invalid @enderror"
               value="{{ old('date_entretien', isset($entretien) ? $entretien->date_entretien->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        @error('date_entretien') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Kilométrage à l'entretien <span class="text-danger">*</span></label>
        <input type="number" name="kilometrage" id="kilometrage" class="form-control @error('kilometrage') is-invalid @enderror"
               value="{{ old('kilometrage', $entretien->kilometrage ?? '') }}" min="0" required>
        @error('kilometrage') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Coût (FCFA)</label>
        <input type="number" step="0.01" name="cout" class="form-control @error('cout') is-invalid @enderror"
               value="{{ old('cout', $entretien->cout ?? 0) }}" min="0">
        @error('cout') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="2">{{ old('description', $entretien->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="alert alert-info small">
    <i class="fa-solid fa-bell"></i> <strong>Seuils d'alerte (US10)</strong> — pré-remplis automatiquement selon
    le type sélectionné, modifiables manuellement.
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Prochain entretien — seuil kilométrique</label>
        <input type="number" name="prochain_kilometrage_seuil" id="prochain_kilometrage_seuil"
               class="form-control @error('prochain_kilometrage_seuil') is-invalid @enderror"
               value="{{ old('prochain_kilometrage_seuil', $entretien->prochain_kilometrage_seuil ?? '') }}" min="0">
        @error('prochain_kilometrage_seuil') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Prochain entretien — date prévue</label>
        <input type="date" name="prochaine_date_prevue" id="prochaine_date_prevue"
               class="form-control @error('prochaine_date_prevue') is-invalid @enderror"
               value="{{ old('prochaine_date_prevue', isset($entretien) ? optional($entretien->prochaine_date_prevue)->format('Y-m-d') : '') }}">
        @error('prochaine_date_prevue') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const typeSelect = document.getElementById('type');
            const kmInput = document.getElementById('kilometrage');
            const dateInput = document.getElementById('date_entretien');
            const seuilKmInput = document.getElementById('prochain_kilometrage_seuil');
            const seuilDateInput = document.getElementById('prochaine_date_prevue');

            function preRemplirSeuils() {
                const option = typeSelect.options[typeSelect.selectedIndex];
                if (!option || !option.value) return;

                const intervalleKm = parseInt(option.dataset.intervalleKm, 10);
                const intervalleJours = parseInt(option.dataset.intervalleJours, 10);
                const km = parseInt(kmInput.value, 10);

                if (!seuilKmInput.value && !isNaN(intervalleKm) && !isNaN(km)) {
                    seuilKmInput.value = km + intervalleKm;
                }
                if (!seuilDateInput.value && !isNaN(intervalleJours) && dateInput.value) {
                    const d = new Date(dateInput.value);
                    d.setDate(d.getDate() + intervalleJours);
                    seuilDateInput.value = d.toISOString().slice(0, 10);
                }
            }

            typeSelect?.addEventListener('change', preRemplirSeuils);
            kmInput?.addEventListener('change', preRemplirSeuils);
        });
    </script>
@endpush
