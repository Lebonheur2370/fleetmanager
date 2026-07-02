<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Vehicule;
use App\Notifications\AlerteEntretienNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VerifierAlertesEntretiens extends Command
{
    protected $signature = 'entretiens:verifier-alertes';

    protected $description = "US10 — Vérifie chaque véhicule par rapport au seuil kilométrique / à la date "
        ."d'entretien prévue, et notifie les gestionnaires (email + notification en base) en cas d'échéance imminente ou dépassée.";

    public function handle(): int
    {
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('Aucun administrateur à notifier.');

            return self::SUCCESS;
        }

        $margeKm = (int) config('entretiens.marge_km', 500);
        $margeJours = (int) config('entretiens.marge_jours', 7);
        $compteur = 0;

        Vehicule::where('statut', '!=', 'hors_service')->each(function (Vehicule $vehicule) use ($admins, $margeKm, $margeJours, &$compteur) {
            // On se base sur le dernier entretien ayant un seuil défini (US10).
            $dernierEntretien = $vehicule->entretiens()
                ->where(function ($q) {
                    $q->whereNotNull('prochain_kilometrage_seuil')
                        ->orWhereNotNull('prochaine_date_prevue');
                })
                ->latest('date_entretien')
                ->first();

            if (! $dernierEntretien) {
                return;
            }

            $motifs = [];

            if ($dernierEntretien->prochain_kilometrage_seuil !== null) {
                $ecart = $dernierEntretien->prochain_kilometrage_seuil - $vehicule->kilometrage;
                if ($ecart <= $margeKm) {
                    $motifs[] = $ecart <= 0
                        ? 'seuil kilométrique dépassé de '.abs($ecart).' km'
                        : "seuil kilométrique atteint dans {$ecart} km";
                }
            }

            if ($dernierEntretien->prochaine_date_prevue !== null) {
                $joursRestants = (int) floor(
                    ($dernierEntretien->prochaine_date_prevue->copy()->startOfDay()->timestamp - now()->startOfDay()->timestamp) / 86400
                );
                if ($joursRestants <= $margeJours) {
                    $motifs[] = $joursRestants <= 0
                        ? "date d'entretien dépassée depuis ".abs($joursRestants).' jour(s)'
                        : "date d'entretien prévue dans {$joursRestants} jour(s)";
                }
            }

            if (empty($motifs) || $this->alerteDejaEnvoyeeAujourdhui($vehicule->id)) {
                return;
            }

            foreach ($admins as $admin) {
                $admin->notify(new AlerteEntretienNotification($vehicule, $dernierEntretien, $motifs));
            }

            $compteur++;
        });

        $this->info("{$compteur} alerte(s) d'entretien envoyée(s).");

        return self::SUCCESS;
    }

    private function alerteDejaEnvoyeeAujourdhui(int $vehiculeId): bool
    {
        return DB::table('notifications')
            ->where('type', AlerteEntretienNotification::class)
            ->whereDate('created_at', today())
            ->get()
            ->contains(fn ($n) => (json_decode($n->data, true)['vehicule_id'] ?? null) === $vehiculeId);
    }
}
