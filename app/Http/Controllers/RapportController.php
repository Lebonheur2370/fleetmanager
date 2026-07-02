<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;

class RapportController extends Controller
{
    /**
     * US13 — « Étant donné des entretiens et des pleins enregistrés, quand le
     * gestionnaire ouvre le tableau de bord, alors les dépenses cumulées par
     * véhicule s'affichent. »
     */
    public function depenses()
    {
        $vehicules = $this->vehiculesAvecDepenses()->sortByDesc('cout_total')->values();
        $totalGeneral = $vehicules->sum('cout_total');

        return view('rapports.depenses', compact('vehicules', 'totalGeneral'));
    }

    /**
     * US13 (sous-tâche) — Export CSV du rapport des dépenses.
     */
    public function exporterDepenses()
    {
        $vehicules = $this->vehiculesAvecDepenses()->sortByDesc('cout_total')->values();
        $nomFichier = 'depenses-vehicules-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($vehicules) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // BOM UTF-8 pour une ouverture correcte dans Excel

            fputcsv($handle, ['Immatriculation', 'Marque', 'Modèle', 'Entretiens (FCFA)', 'Carburant (FCFA)', 'Total (FCFA)'], ';');

            foreach ($vehicules as $vehicule) {
                fputcsv($handle, [
                    $vehicule->immatriculation,
                    $vehicule->marque,
                    $vehicule->modele,
                    (int) $vehicule->cout_entretiens,
                    (int) $vehicule->cout_carburant,
                    (int) $vehicule->cout_total,
                ], ';');
            }

            fclose($handle);
        }, $nomFichier, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * US14 — « Étant donné les kilométrages de tous les véhicules, quand le
     * gestionnaire ouvre le dashboard, alors le kilométrage cumulé du parc
     * s'affiche. »
     */
    public function kilometrage()
    {
        $vehicules = Vehicule::orderByDesc('kilometrage')->get();
        $kilometrageTotal = $vehicules->sum('kilometrage');
        $kilometrageMoyen = $vehicules->count() > 0
            ? (int) round($kilometrageTotal / $vehicules->count())
            : 0;

        return view('rapports.kilometrage', compact('vehicules', 'kilometrageTotal', 'kilometrageMoyen'));
    }

    /**
     * Agrège les coûts entretiens + carburant par véhicule.
     */
    private function vehiculesAvecDepenses()
    {
        return Vehicule::withSum('entretiens as cout_entretiens', 'cout')
            ->withSum('pleins as cout_carburant', 'montant')
            ->get()
            ->map(function (Vehicule $vehicule) {
                $vehicule->cout_entretiens = (float) ($vehicule->cout_entretiens ?? 0);
                $vehicule->cout_carburant = (float) ($vehicule->cout_carburant ?? 0);
                $vehicule->cout_total = $vehicule->cout_entretiens + $vehicule->cout_carburant;

                return $vehicule;
            });
    }
}
