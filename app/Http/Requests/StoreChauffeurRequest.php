<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChauffeurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * US4 — « Étant donné un nom, un permis et un téléphone valides, quand le
     * gestionnaire valide le formulaire, alors le chauffeur est créé. »
     * L'email/mot de passe ne sont pas saisis : ils sont auto-générés (cf. ChauffeurController).
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'numero_permis' => ['required', 'string', 'max:50', 'unique:chauffeurs,numero_permis'],
            'date_expiration_permis' => ['nullable', 'date'],
            'telephone' => ['required', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => "Le nom est obligatoire.",
            'prenom.required' => "Le prénom est obligatoire.",
            'numero_permis.required' => "Le numéro de permis est obligatoire.",
            'numero_permis.unique' => "Ce numéro de permis est déjà enregistré.",
            'telephone.required' => "Le numéro de téléphone est obligatoire.",
        ];
    }
}
