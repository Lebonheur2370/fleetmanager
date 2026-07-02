<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePleinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vehicule_id' => ['required', 'exists:vehicules,id'],
            'chauffeur_id' => ['nullable', 'exists:chauffeurs,id'],
            'date_plein' => ['required', 'date', 'before_or_equal:today'],
            'litres' => ['required', 'numeric', 'min:0.01', 'max:9999'],
            'montant' => ['required', 'numeric', 'min:0'],
            'kilometrage' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'vehicule_id.required' => 'Veuillez sélectionner un véhicule.',
            'date_plein.required' => 'La date du plein est obligatoire.',
            'litres.required' => 'Le volume en litres est obligatoire.',
            'montant.required' => 'Le montant est obligatoire.',
            'kilometrage.required' => 'Le kilométrage est obligatoire.',
        ];
    }
}
