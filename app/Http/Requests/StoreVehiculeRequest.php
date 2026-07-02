<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehiculeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * US1 — Critères Gherkin :
     * - immatriculation obligatoire (sinon erreur de saisie obligatoire)
     * - immatriculation, marque, modèle, kilométrage valides pour l'ajout
     */
    public function rules(): array
    {
        return [
            'immatriculation' => ['required', 'string', 'max:20', 'unique:vehicules,immatriculation'],
            'marque' => ['required', 'string', 'max:100'],
            'modele' => ['required', 'string', 'max:100'],
            'annee' => ['nullable', 'integer', 'min:1980', 'max:'.(date('Y') + 1)],
            'kilometrage' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'immatriculation.required' => 'Le champ immatriculation est obligatoire.',
            'immatriculation.unique' => 'Cette immatriculation est déjà enregistrée.',
            'marque.required' => 'La marque est obligatoire.',
            'modele.required' => 'Le modèle est obligatoire.',
            'kilometrage.required' => 'Le kilométrage est obligatoire.',
            'kilometrage.integer' => 'Le kilométrage doit être un nombre entier.',
        ];
    }
}
