<?php

return [
    'required' => "Le champ :attribute est obligatoire.",
    'email' => "Le champ :attribute doit être une adresse email valide.",
    'unique' => "Cette valeur pour :attribute est déjà utilisée.",
    'confirmed' => "La confirmation de :attribute ne correspond pas.",
    'min' => [
        'string' => "Le champ :attribute doit contenir au moins :min caractères.",
        'numeric' => "Le champ :attribute doit être au moins :min.",
    ],
    'max' => [
        'string' => "Le champ :attribute ne doit pas dépasser :max caractères.",
        'numeric' => "Le champ :attribute ne doit pas dépasser :max.",
    ],
    'numeric' => "Le champ :attribute doit être un nombre.",
    'date' => "Le champ :attribute doit être une date valide.",
    'in' => "La valeur sélectionnée pour :attribute est invalide.",
    'exists' => "La valeur sélectionnée pour :attribute est invalide.",
    'string' => "Le champ :attribute doit être une chaîne de caractères.",
    'regex' => "Le format du champ :attribute est invalide.",

    'attributes' => [
        'email' => 'email',
        'password' => 'mot de passe',
        'name' => 'nom',
        'nom' => 'nom',
        'prenom' => 'prénom',
        'immatriculation' => 'immatriculation',
        'marque' => 'marque',
        'modele' => 'modèle',
        'kilometrage' => 'kilométrage',
        'numero_permis' => 'numéro de permis',
        'telephone' => 'téléphone',
        'date_debut' => 'date de début',
        'date_fin' => 'date de fin',
        'type' => 'type',
        'cout' => 'coût',
        'litres' => 'litres',
        'montant' => 'montant',
    ],
];
