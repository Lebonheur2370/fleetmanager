<?php

namespace App\Http\Requests;

use App\Models\Plein;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StorePleinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * US11 — « Étant donné un véhicule existant, quand le gestionnaire saisit la
     * date, le volume et le montant du plein, alors le plein est enregistré. »
     */
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
            'date_plein.before_or_equal' => 'La date du plein ne peut pas être dans le futur.',
            'litres.required' => 'Le volume en litres est obligatoire.',
            'montant.required' => 'Le montant est obligatoire.',
            'kilometrage.required' => 'Le kilométrage est obligatoire.',
        ];
    }

    /**
     * Règle de cohérence des données : le kilométrage saisi ne peut pas être
     * inférieur à un plein déjà enregistré pour ce véhicule.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (! $this->vehicule_id || ! $this->kilometrage) {
                return;
            }

            $dernierKilometrage = Plein::where('vehicule_id', $this->vehicule_id)->max('kilometrage');

            if ($dernierKilometrage !== null && (int) $this->kilometrage < $dernierKilometrage) {
                $validator->errors()->add(
                    'kilometrage',
                    "Le kilométrage doit être supérieur ou égal au dernier plein enregistré pour ce véhicule ({$dernierKilometrage} km)."
                );
            }
        });
    }
}
