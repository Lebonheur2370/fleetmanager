<?php

namespace App\Http\Controllers;

use App\Models\Affectation;
use App\Models\Chauffeur;
use App\Models\Entretien;
use App\Models\Plein;
use App\Models\Vehicule;
use App\Support\EntretienAlerteHelper;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (! auth()->user()->isAdmin()) {
            return view('dashboard.chauffeur');
        }

        return view('dashboard.admin', [
            'stats' => $this->statsPrincipales(),
            'vehiculesParStatut' => $this->vehiculesParStatut(),
            'depensesParMois' => $this->depensesParMois(),
            'topVehicules' => $this->topVehiculesCouteux(),
            'alertesEntretien' => $this->alertesEntretien(),
            'permisExpirants' => $this->permisExpirantsBientot(),
            'dernieresAffectations' => Affectation::with(['vehicule', 'chauffeur'])
                ->latest('date_debut')->take(5)->get(),
        ]);
    }

    private function statsPrincipales(): array
    {
        $debutMois = now()->startOfMonth();

        return [
            'nombreVehicules' => Vehicule::count(),
            'vehiculesDisponibles' => Vehicule::where('statut', 'disponible')->count(),
            'nombreChauffeurs' => Chauffeur::count(),
            'chauffeursDisponibles' => Chauffeur::where('disponibilite', 'disponible')->count(),
            'affectationsActives' => Affectation::where('statut', 'active')->count(),
            'kilometrageTotal' => (int) Vehicule::sum('kilometrage'),
            'depensesTotal' => (float) Entretien::sum('cout') + (float) Plein::sum('montant'),
            'depensesMoisCourant' => (float) Entretien::where('date_entretien', '>=', $debutMois)->sum('cout')
                + (float) Plein::where('date_plein', '>=', $debutMois)->sum('montant'),
        ];
    }

    private function vehiculesParStatut(): array
    {
        $statuts = ['disponible' => 'Disponible', 'affecte' => 'Affecté', 'en_entretien' => 'En entretien', 'hors_service' => 'Hors service'];
        $comptes = Vehicule::selectRaw('statut, count(*) as total')->groupBy('statut')->pluck('total', 'statut');

        return collect($statuts)->map(fn ($label, $cle) => [
            'label' => $label,
            'total' => (int) ($comptes[$cle] ?? 0),
        ])->values()->all();
    }

    /**
     * Dépenses (entretiens + carburant) des 6 derniers mois, agrégées en PHP
     * pour rester compatible SQLite (tests) et MySQL (production).
     */
    private function depensesParMois(): array
    {
        $debut = now()->startOfMonth()->subMonths(5);

        $entretiens = Entretien::where('date_entretien', '>=', $debut)->get(['date_entretien', 'cout']);
        $pleins = Plein::where('date_plein', '>=', $debut)->get(['date_plein', 'montant']);

        $mois = collect(range(0, 5))->map(fn ($i) => $debut->copy()->addMonths($i));

        return $mois->map(function (Carbon $date) use ($entretiens, $pleins) {
            $totalEntretiens = $entretiens
                ->filter(fn ($e) => $e->date_entretien->isSameMonth($date))
                ->sum('cout');

            $totalCarburant = $pleins
                ->filter(fn ($p) => $p->date_plein->isSameMonth($date))
                ->sum('montant');

            return [
                'label' => $date->translatedFormat('M Y'),
                'entretiens' => (float) $totalEntretiens,
                'carburant' => (float) $totalCarburant,
            ];
        })->all();
    }

    private function topVehiculesCouteux(int $limite = 5): array
    {
        return Vehicule::withSum('entretiens as cout_entretiens', 'cout')
            ->withSum('pleins as cout_carburant', 'montant')
            ->get()
            ->map(function (Vehicule $vehicule) {
                $total = (float) ($vehicule->cout_entretiens ?? 0) + (float) ($vehicule->cout_carburant ?? 0);

                return ['immatriculation' => $vehicule->immatriculation, 'total' => $total];
            })
            ->sortByDesc('total')
            ->take($limite)
            ->values()
            ->all();
    }

    /**
     * Véhicules dont le dernier entretien signale un seuil proche ou dépassé (US10).
     */
    private function alertesEntretien(int $limite = 5)
    {
        return EntretienAlerteHelper::vehiculesConcernes($limite);
    }

    private function permisExpirantsBientot(int $limite = 5)
    {
        return Chauffeur::whereNotNull('date_expiration_permis')
            ->where('date_expiration_permis', '<=', now()->addDays(30))
            ->orderBy('date_expiration_permis')
            ->take($limite)
            ->get();
    }
}
