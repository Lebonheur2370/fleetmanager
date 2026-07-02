<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehiculeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * US2 — « Étant donné un véhicule existant, quand le gestionnaire modifie
     * un champ et valide, alors les nouvelles informations sont enregistrées. »
     */
    public function rules(): array
    {
        $vehicule = $this->route('vehicule');

        return [
            'immatriculation' => [
                'required', 'string', 'max:20',
                Rule::unique('vehicules', 'immatriculation')->ignore($vehicule->id),
            ],
            'marque' => ['required', 'string', 'max:100'],
            'modele' => ['required', 'string', 'max:100'],
            'annee' => ['nullable', 'integer', 'min:1980', 'max:'.(date('Y') + 1)],
            'kilometrage' => ['required', 'integer', 'min:0'],
            'statut' => ['required', Rule::in(['disponible', 'affecte', 'en_entretien', 'hors_service'])],
        ];
    }

    public function messages(): array
    {
        return [
            'immatriculation.required' => "Le champ immatriculation est obligatoire.",
            'immatriculation.unique' => "Cette immatriculation est déjà utilisée par un autre véhicule.",
            'marque.required' => "La marque est obligatoire.",
            'modele.required' => "Le modèle est obligatoire.",
            'kilometrage.required' => "Le kilométrage est obligatoire.",
            'statut.required' => "Le statut est obligatoire.",
        ];
    }
}
