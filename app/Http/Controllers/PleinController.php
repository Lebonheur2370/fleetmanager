<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePleinRequest;
use App\Http\Requests\UpdatePleinRequest;
use App\Models\Chauffeur;
use App\Models\Plein;
use App\Models\Vehicule;

class PleinController extends Controller
{
    /**
     * US11 (complément) — Liste des pleins enregistrés.
     */
    public function index()
    {
        $pleins = Plein::with(['vehicule', 'chauffeur'])->orderByDesc('date_plein')->get();

        return view('pleins.index', compact('pleins'));
    }

    /**
     * US11 — Formulaire de saisie d'un plein.
     */
    public function create()
    {
        $vehicules = Vehicule::orderBy('immatriculation')->get();
        $chauffeurs = Chauffeur::orderBy('nom')->get();

        return view('pleins.create', compact('vehicules', 'chauffeurs'));
    }

    /**
     * US11 — Enregistrer un plein de carburant.
     * Le kilométrage du véhicule est mis à jour si la valeur saisie est plus
     * récente (le plein est la lecture de compteur la plus fiable disponible).
     */
    public function store(StorePleinRequest $request)
    {
        $plein = Plein::create($request->validated());

        $vehicule = $plein->vehicule;
        if ($plein->kilometrage > $vehicule->kilometrage) {
            $vehicule->update(['kilometrage' => $plein->kilometrage]);
        }

        return redirect()->route('pleins.index')
            ->with('success', "Le plein a été enregistré pour {$vehicule->immatriculation}.");
    }

    public function show(Plein $plein)
    {
        $plein->load('vehicule', 'chauffeur');

        return view('pleins.show', compact('plein'));
    }

    public function edit(Plein $plein)
    {
        $vehicules = Vehicule::orderBy('immatriculation')->get();
        $chauffeurs = Chauffeur::orderBy('nom')->get();

        return view('pleins.edit', compact('plein', 'vehicules', 'chauffeurs'));
    }

    public function update(UpdatePleinRequest $request, Plein $plein)
    {
        $plein->update($request->validated());

        return redirect()->route('pleins.index')->with('success', 'Le plein a été mis à jour.');
    }

    public function destroy(Plein $plein)
    {
        $plein->delete();

        return redirect()->route('pleins.index')->with('success', 'Le plein a été supprimé.');
    }

    /**
     * US12 — « Étant donné l'historique des pleins d'un véhicule, quand le
     * gestionnaire consulte sa fiche, alors la consommation moyenne en
     * L/100km s'affiche. » — Vue consolidée par véhicule.
     */
    public function consommation()
    {
        $vehicules = Vehicule::withCount('pleins')->get()->map(fn (Vehicule $vehicule) => [
            'vehicule' => $vehicule,
            'consommation' => $vehicule->consommationMoyenne(),
            'depenses' => $vehicule->depensesTotales(),
        ]);

        return view('pleins.consommation', compact('vehicules'));
    }
}
