<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehiculeRequest;
use App\Http\Requests\UpdateVehiculeRequest;
use App\Models\Vehicule;

class VehiculeController extends Controller
{
    /**
     * US3 — Consulter la liste des véhicules.
     * « Étant donné une liste de véhicules enregistrés, quand le gestionnaire
     * ouvre la page véhicules, alors tous les véhicules s'affichent avec leur statut. »
     */
    public function index()
    {
        $vehicules = Vehicule::orderByDesc('created_at')->get();

        return view('vehicules.index', compact('vehicules'));
    }

    /**
     * US1 — Formulaire d'ajout d'un véhicule.
     */
    public function create()
    {
        return view('vehicules.create');
    }

    /**
     * US1 — Enregistrer un véhicule.
     */
    public function store(StoreVehiculeRequest $request)
    {
        Vehicule::create($request->validated());

        return redirect()->route('vehicules.index')
            ->with('success', "Le véhicule a été ajouté à la liste.");
    }

    /**
     * US3 (complément) — Fiche détaillée d'un véhicule.
     */
    public function show(Vehicule $vehicule)
    {
        return view('vehicules.show', compact('vehicule'));
    }

    /**
     * US2 — Formulaire d'édition pré-rempli.
     */
    public function edit(Vehicule $vehicule)
    {
        return view('vehicules.edit', compact('vehicule'));
    }

    /**
     * US2 — Modifier un véhicule.
     */
    public function update(UpdateVehiculeRequest $request, Vehicule $vehicule)
    {
        $vehicule->update($request->validated());

        return redirect()->route('vehicules.index')
            ->with('success', "Les informations du véhicule ont été mises à jour.");
    }

    /**
     * Suppression (soft delete) d'un véhicule.
     */
    public function destroy(Vehicule $vehicule)
    {
        $vehicule->delete();

        return redirect()->route('vehicules.index')
            ->with('success', "Le véhicule {$vehicule->immatriculation} a été supprimé.");
    }
}
