<?php

namespace App\Services;

use App\Models\Chauffeur;
use Illuminate\Support\Str;

/**
 * Génère automatiquement le matricule, le login et le mot de passe
 * d'un chauffeur à partir de son nom et prénom (US4 — Créer un chauffeur).
 *
 * Format :
 *  - matricule : CHF-0007
 *  - login     : prenom.nom7@fleetmanager.ga
 *  - password  : PrenomNom7  (communiqué à l'admin pour transmission au chauffeur)
 */
class CredentialGeneratorService
{
    public function generate(string $prenom, string $nom): array
    {
        $numero = $this->prochainNumero();
        $numeroFormate = str_pad((string) $numero, 4, '0', STR_PAD_LEFT);

        $prenomSlug = Str::slug($prenom, '');
        $nomSlug = Str::slug($nom, '');

        $domaine = config('app.fleet_login_domain', 'fleetmanager.ga');

        return [
            'matricule' => "CHF-{$numeroFormate}",
            'login' => strtolower("{$prenomSlug}.{$nomSlug}{$numero}@{$domaine}"),
            'password' => ucfirst(strtolower($prenomSlug)) . ucfirst(strtolower($nomSlug)) . $numero,
        ];
    }

    /**
     * Numéro séquentiel basé sur le nombre de chauffeurs déjà créés
     * (y compris ceux supprimés en soft-delete, pour garantir l'unicité).
     */
    protected function prochainNumero(): int
    {
        return Chauffeur::withTrashed()->count() + 1;
    }
}
