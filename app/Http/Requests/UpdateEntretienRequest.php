<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEntretienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicule_id' => ['required', 'exists:vehicules,id'],
            'type' => ['required', Rule::in(array_keys(config('entretiens.types')))],
            'date_entretien' => ['required', 'date'],
            'kilometrage' => ['required', 'integer', 'min:0'],
            'cout' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'prochain_kilometrage_seuil' => ['nullable', 'integer', 'gt:kilometrage'],
            'prochaine_date_prevue' => ['nullable', 'date', 'after:date_entretien'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicule_id.required' => "Veuillez sélectionner un véhicule.",
            'type.required' => "Le type d'entretien est obligatoire.",
            'date_entretien.required' => "La date de l'entretien est obligatoire.",
            'kilometrage.required' => "Le kilométrage est obligatoire.",
            'prochain_kilometrage_seuil.gt' => "Le seuil du prochain entretien doit être supérieur au kilométrage actuel.",
            'prochaine_date_prevue.after' => "La prochaine date prévue doit être postérieure à la date de l'entretien.",
        ];
    }
}
