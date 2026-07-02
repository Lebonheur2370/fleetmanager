<?php

namespace App\Http\Requests;

use App\Models\Affectation;
use Illuminate\Foundation\Http\FormRequest;

class StoreAffectationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * US7 — « Étant donné un véhicule disponible et un chauffeur disponible,
     * quand le gestionnaire valide l'affectation, alors elle est enregistrée
     * avec sa date de début. »
     */
    public function rules(): array
    {
        return [
            'vehicule_id' => ['required', 'exists:vehicules,id'],
            'chauffeur_id' => ['required', 'exists:chauffeurs,id'],
            'date_debut' => ['required', 'date'],
            'date_fin' => ['nullable', 'date', 'after:date_debut'],
            'motif' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicule_id.required' => "Veuillez sélectionner un véhicule.",
            'chauffeur_id.required' => "Veuillez sélectionner un chauffeur.",
            'date_debut.required' => "La date de début est obligatoire.",
            'date_fin.after' => "La date de fin doit être postérieure à la date de début.",
        ];
    }

    /**
     * US7 (contre-cas) — « Étant donné un véhicule déjà affecté sur la période
     * choisie, quand le gestionnaire tente une nouvelle affectation, alors un
     * message d'indisponibilité s'affiche. »
     * (Doublon de sécurité au niveau du modèle Affectation::booted().)
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->filled('vehicule_id') && Affectation::where('vehicule_id', $this->vehicule_id)
                ->where('statut', 'active')->exists()) {
                $validator->errors()->add('vehicule_id', "Ce véhicule est déjà affecté sur une période en cours.");
            }

            if ($this->filled('chauffeur_id') && Affectation::where('chauffeur_id', $this->chauffeur_id)
                ->where('statut', 'active')->exists()) {
                $validator->errors()->add('chauffeur_id', "Ce chauffeur est déjà affecté sur une période en cours.");
            }
        });
    }
}
