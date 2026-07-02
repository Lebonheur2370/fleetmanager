<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChauffeurRequest;
use App\Http\Requests\UpdateChauffeurRequest;
use App\Models\Chauffeur;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ChauffeurController extends Controller
{
    /**
     * US6 — Lister les chauffeurs.
     * « Étant donné des chauffeurs enregistrés, quand le gestionnaire ouvre la
     * page chauffeurs, alors la liste complète s'affiche. »
     */
    public function index()
    {
        $chauffeurs = Chauffeur::with('user')->orderByDesc('created_at')->get();

        return view('chauffeurs.index', compact('chauffeurs'));
    }

    /**
     * US4 — Formulaire de création d'un chauffeur.
     */
    public function create()
    {
        return view('chauffeurs.create');
    }

    /**
     * US4 — Créer un chauffeur.
     * Le compte utilisateur (email + mot de passe temporaire) est généré
     * automatiquement : le chauffeur devra le changer à sa première connexion
     * (doit_changer_mot_de_passe, cf. Epic 0 / PasswordChangeController).
     */
    public function store(StoreChauffeurRequest $request)
    {
        $data = $request->validated();

        [$chauffeur, $motDePasseTemporaire] = DB::transaction(function () use ($data) {
            $email = $this->genererEmailUnique($data['prenom'], $data['nom']);
            $motDePasse = Str::password(10);

            $user = User::create([
                'name' => "{$data['prenom']} {$data['nom']}",
                'email' => $email,
                'password' => Hash::make($motDePasse),
                'role' => 'chauffeur',
                'doit_changer_mot_de_passe' => true,
            ]);

            $chauffeur = Chauffeur::create([
                ...$data,
                'user_id' => $user->id,
                'matricule' => $this->genererMatricule(),
            ]);

            return [$chauffeur, $motDePasse];
        });

        return redirect()->route('chauffeurs.index')->with('success', "Le chauffeur {$chauffeur->nomComplet()} a été créé.")
            ->with('identifiants', [
                'email' => $chauffeur->user->email,
                'password' => $motDePasseTemporaire,
            ]);
    }

    /**
     * US6 (complément) — Fiche détaillée d'un chauffeur.
     */
    public function show(Chauffeur $chauffeur)
    {
        $chauffeur->load(['user', 'affectationActive.vehicule']);

        return view('chauffeurs.show', compact('chauffeur'));
    }

    /**
     * US5 — Formulaire d'édition.
     */
    public function edit(Chauffeur $chauffeur)
    {
        return view('chauffeurs.edit', compact('chauffeur'));
    }

    /**
     * US5 — Modifier un chauffeur.
     */
    public function update(UpdateChauffeurRequest $request, Chauffeur $chauffeur)
    {
        $chauffeur->update($request->validated());

        return redirect()->route('chauffeurs.index')
            ->with('success', "Les informations de {$chauffeur->nomComplet()} ont été mises à jour.");
    }

    /**
     * Suppression (soft delete) d'un chauffeur. Le compte utilisateur associé
     * est désactivé en cascade au niveau base de données.
     */
    public function destroy(Chauffeur $chauffeur)
    {
        $chauffeur->delete();

        return redirect()->route('chauffeurs.index')
            ->with('success', "Le chauffeur {$chauffeur->nomComplet()} a été supprimé.");
    }

    private function genererMatricule(): string
    {
        $dernier = Chauffeur::withTrashed()->count() + 1;

        return 'CHF-'.str_pad((string) $dernier, 4, '0', STR_PAD_LEFT);
    }

    private function genererEmailUnique(string $prenom, string $nom): string
    {
        $base = Str::slug("{$prenom}.{$nom}", '.');
        $email = "{$base}@fleetmanager.ga";
        $compteur = 1;

        while (User::where('email', $email)->exists()) {
            $email = "{$base}{$compteur}@fleetmanager.ga";
            $compteur++;
        }

        return $email;
    }
}
