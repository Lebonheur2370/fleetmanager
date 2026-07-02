<?php

namespace App\Support;

use App\Models\Vehicule;
use Illuminate\Support\Collection;

class EntretienAlerteHelper
{
    /**
     * Retourne les véhicules dont le dernier entretien avec seuil défini
     * approche ou dépasse la limite (kilométrique ou date), avec le motif.
     */
    public static function vehiculesConcernes(?int $limite = null): Collection
    {
        $margeKm = (int) config('entretiens.marge_km', 500);
        $margeJours = (int) config('entretiens.marge_jours', 7);

        $resultat = Vehicule::with(['entretiens' => function ($q) {
            $q->where(function ($q2) {
                $q2->whereNotNull('prochain_kilometrage_seuil')
                    ->orWhereNotNull('prochaine_date_prevue');
            })->latest('date_entretien')->limit(1);
        }])
            ->get()
            ->map(function (Vehicule $vehicule) use ($margeKm, $margeJours) {
                $entretien = $vehicule->entretiens->first();
                if (! $entretien) {
                    return null;
                }

                if ($entretien->prochain_kilometrage_seuil !== null
                    && ($entretien->prochain_kilometrage_seuil - $vehicule->kilometrage) <= $margeKm) {
                    return [
                        'vehicule' => $vehicule,
                        'motif' => 'kilométrage',
                        'urgent' => $vehicule->kilometrage >= $entretien->prochain_kilometrage_seuil,
                    ];
                }

                if ($entretien->prochaine_date_prevue !== null) {
                    $jours = (int) floor(
                        ($entretien->prochaine_date_prevue->copy()->startOfDay()->timestamp - now()->startOfDay()->timestamp) / 86400
                    );
                    if ($jours <= $margeJours) {
                        return ['vehicule' => $vehicule, 'motif' => 'date', 'urgent' => $jours <= 0];
                    }
                }

                return null;
            })
            ->filter()
            ->values();

        return $limite ? $resultat->take($limite) : $resultat;
    }
}
