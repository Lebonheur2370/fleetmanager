<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAffectationRequest;
use App\Models\Affectation;
use App\Models\Chauffeur;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffectationController extends Controller
{
    /**
     * US8 — Historiser les affectations.
     * « Étant donné des affectations passées, quand le gestionnaire filtre par
     * véhicule ou par chauffeur, alors l'historique correspondant s'affiche. »
     * Le paramètre ?export=1 déclenche le téléchargement CSV du résultat filtré.
     */
    public function index(Request $request)
    {
        $query = Affectation::with(['vehicule', 'chauffeur'])->orderByDesc('date_debut');

        if ($request->filled('vehicule_id')) {
            $query->where('vehicule_id', $request->integer('vehicule_id'));
        }
        if ($request->filled('chauffeur_id')) {
            $query->where('chauffeur_id', $request->integer('chauffeur_id'));
        }
        if ($request->filled('date_de')) {
            $query->whereDate('date_debut', '>=', $request->date('date_de'));
        }
        if ($request->filled('date_a')) {
            $query->whereDate('date_debut', '<=', $request->date('date_a'));
        }

        $affectations = $query->get();

        if ($request->boolean('export')) {
            return $this->exportCsv($affectations);
        }

        $vehicules = Vehicule::orderBy('immatriculation')->get();
        $chauffeurs = Chauffeur::orderBy('nom')->get();

        return view('affectations.index', compact('affectations', 'vehicules', 'chauffeurs'));
    }

    /**
     * US7 — Formulaire de sélection véhicule/chauffeur.
     * Seuls les véhicules et chauffeurs actuellement disponibles sont proposés.
     */
    public function create()
    {
        $vehicules = Vehicule::where('statut', 'disponible')->orderBy('immatriculation')->get();
        $chauffeurs = Chauffeur::where('disponibilite', 'disponible')->orderBy('nom')->get();

        return view('affectations.create', compact('vehicules', 'chauffeurs'));
    }

    /**
     * US7 — Affecter un véhicule à un chauffeur.
     */
    public function store(StoreAffectationRequest $request)
    {
        $affectation = DB::transaction(function () use ($request) {
            $affectation = Affectation::create($request->validated());

            $affectation->vehicule()->update(['statut' => 'affecte']);
            $affectation->chauffeur()->update(['disponibilite' => 'indisponible']);

            return $affectation;
        });

        return redirect()->route('affectations.index')->with(
            'success',
            "Le véhicule {$affectation->vehicule->immatriculation} a été affecté à {$affectation->chauffeur->nomComplet()}."
        );
    }

    /**
     * US8 (complément) — Fiche détaillée d'une affectation.
     */
    public function show(Affectation $affectation)
    {
        $affectation->load(['vehicule', 'chauffeur']);

        return view('affectations.show', compact('affectation'));
    }

    /**
     * Clôture une affectation active : libère le véhicule et le chauffeur.
     */
    public function cloturer(Affectation $affectation)
    {
        if ($affectation->statut !== 'active') {
            return back()->with('error', 'Cette affectation est déjà clôturée.');
        }

        DB::transaction(function () use ($affectation) {
            $affectation->cloturer();
            $affectation->vehicule()->update(['statut' => 'disponible']);
            $affectation->chauffeur()->update(['disponibilite' => 'disponible']);
        });

        return redirect()->route('affectations.index')
            ->with('success', "L'affectation a été clôturée. Le véhicule et le chauffeur sont de nouveau disponibles.");
    }

    /**
     * Suppression d'une affectation (correction d'erreur de saisie).
     * Si elle était active, le véhicule et le chauffeur sont automatiquement libérés.
     */
    public function destroy(Affectation $affectation)
    {
        DB::transaction(function () use ($affectation) {
            if ($affectation->statut === 'active') {
                $affectation->vehicule()->update(['statut' => 'disponible']);
                $affectation->chauffeur()->update(['disponibilite' => 'disponible']);
            }

            $affectation->delete();
        });

        return redirect()->route('affectations.index')->with('success', "L'affectation a été supprimée.");
    }

    private function exportCsv($affectations)
    {
        $filename = 'historique_affectations_'.now()->format('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($affectations) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF"); // BOM UTF-8 pour une ouverture correcte dans Excel
            fputcsv($handle, ['Véhicule', 'Chauffeur', 'Date début', 'Date fin', 'Statut', 'Motif'], ';');

            foreach ($affectations as $affectation) {
                fputcsv($handle, [
                    $affectation->vehicule->immatriculation ?? '—',
                    $affectation->chauffeur?->nomComplet() ?? '—',
                    $affectation->date_debut->format('d/m/Y H:i'),
                    $affectation->date_fin?->format('d/m/Y H:i') ?? '—',
                    $affectation->statut === 'active' ? 'Active' : 'Terminée',
                    $affectation->motif ?? '',
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
