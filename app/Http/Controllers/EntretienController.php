<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntretienRequest;
use App\Http\Requests\UpdateEntretienRequest;
use App\Models\Entretien;
use App\Models\Vehicule;

class EntretienController extends Controller
{
    /**
     * US9 (complément) — Liste des entretiens enregistrés.
     */
    public function index()
    {
        $entretiens = Entretien::with('vehicule')->orderByDesc('date_entretien')->get();

        return view('entretiens.index', compact('entretiens'));
    }

    /**
     * US9 — Formulaire de saisie d'un entretien.
     */
    public function create()
    {
        $vehicules = Vehicule::orderBy('immatriculation')->get();
        $types = config('entretiens.types');

        return view('entretiens.create', compact('vehicules', 'types'));
    }

    /**
     * US9 — Créer un entretien.
     * Si les seuils d'alerte (US10) ne sont pas saisis manuellement, ils sont
     * pré-calculés à partir des intervalles par défaut du type sélectionné
     * (cf. config/entretiens.php).
     */
    public function store(StoreEntretienRequest $request)
    {
        $data = $request->validated();
        $data = $this->completerSeuilsParDefaut($data);

        $entretien = Entretien::create($data);

        return redirect()->route('entretiens.index')
            ->with('success', "L'entretien ({$entretien->type}) a été enregistré pour {$entretien->vehicule->immatriculation}.");
    }

    public function show(Entretien $entretien)
    {
        $entretien->load('vehicule');

        return view('entretiens.show', compact('entretien'));
    }

    public function edit(Entretien $entretien)
    {
        $vehicules = Vehicule::orderBy('immatriculation')->get();
        $types = config('entretiens.types');

        return view('entretiens.edit', compact('entretien', 'vehicules', 'types'));
    }

    public function update(UpdateEntretienRequest $request, Entretien $entretien)
    {
        $entretien->update($request->validated());

        return redirect()->route('entretiens.index')
            ->with('success', "L'entretien a été mis à jour.");
    }

    public function destroy(Entretien $entretien)
    {
        $entretien->delete();

        return redirect()->route('entretiens.index')->with('success', "L'entretien a été supprimé.");
    }

    private function completerSeuilsParDefaut(array $data): array
    {
        $config = config("entretiens.types.{$data['type']}");

        if (empty($data['prochain_kilometrage_seuil']) && ! empty($config['intervalle_km'])) {
            $data['prochain_kilometrage_seuil'] = $data['kilometrage'] + $config['intervalle_km'];
        }

        if (empty($data['prochaine_date_prevue']) && ! empty($config['intervalle_jours'])) {
            $data['prochaine_date_prevue'] = \Carbon\Carbon::parse($data['date_entretien'])
                ->addDays($config['intervalle_jours'])
                ->toDateString();
        }

        return $data;
    }
}
