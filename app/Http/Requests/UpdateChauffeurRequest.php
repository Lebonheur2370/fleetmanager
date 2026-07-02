<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChauffeurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * US5 — « Étant donné un chauffeur existant, quand ses informations sont
     * modifiées et validées, alors elles sont mises à jour. »
     */
    public function rules(): array
    {
        $chauffeur = $this->route('chauffeur');

        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'numero_permis' => [
                'required', 'string', 'max:50',
                Rule::unique('chauffeurs', 'numero_permis')->ignore($chauffeur->id),
            ],
            'date_expiration_permis' => ['nullable', 'date'],
            'telephone' => ['required', 'string', 'max:20'],
            'disponibilite' => ['required', Rule::in(['disponible', 'indisponible', 'en_conge'])],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => "Le nom est obligatoire.",
            'prenom.required' => "Le prénom est obligatoire.",
            'numero_permis.required' => "Le numéro de permis est obligatoire.",
            'numero_permis.unique' => "Ce numéro de permis est déjà utilisé par un autre chauffeur.",
            'telephone.required' => "Le numéro de téléphone est obligatoire.",
            'disponibilite.required' => "La disponibilité est obligatoire.",
        ];
    }
}
