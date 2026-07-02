<?php

// US9/US10 — Types d'entretien disponibles et seuils par défaut utilisés
// pour pré-calculer prochain_kilometrage_seuil / prochaine_date_prevue
// lors de la création d'un entretien (surchargeables manuellement dans le formulaire).

return [
    'types' => [
        'vidange' => [
            'label' => 'Vidange',
            'intervalle_km' => 10000,
            'intervalle_jours' => 180,
        ],
        'revision' => [
            'label' => 'Révision générale',
            'intervalle_km' => 20000,
            'intervalle_jours' => 365,
        ],
        'pneus' => [
            'label' => 'Pneus',
            'intervalle_km' => 40000,
            'intervalle_jours' => null,
        ],
        'freins' => [
            'label' => 'Freins',
            'intervalle_km' => 30000,
            'intervalle_jours' => null,
        ],
        'autre' => [
            'label' => 'Autre',
            'intervalle_km' => null,
            'intervalle_jours' => null,
        ],
    ],

    // US10 — Marge d'anticipation avant déclenchement de l'alerte.
    'marge_km' => 500,
    'marge_jours' => 7,
];
