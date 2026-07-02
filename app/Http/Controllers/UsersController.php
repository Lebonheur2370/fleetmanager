<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * US0.1 — Affiche le formulaire d'inscription.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * US0.1 — Traite l'inscription (auto-création d'un compte "admin" /
     * gestionnaire ; les chauffeurs, eux, sont créés par l'admin — cf. Epic 2).
     */
    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', "Bienvenue {$user->name}, votre compte a été créé.");
    }

    /**
     * US0.2 — Affiche le formulaire de connexion.
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * US0.2 — Authentifie l'utilisateur (admin ou chauffeur).
     */
    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => __('auth.failed')])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->doit_changer_mot_de_passe) {
            return redirect()->route('password.change');
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * US0.3 — Déconnecte l'utilisateur.
     */
    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté.');
    }

    /**
     * US0.1 (complément CRUD) — Liste des comptes (réservé à l'admin).
     */
    public function index()
    {
        $users = User::latest()->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * US0.1 (complément CRUD) — Formulaire d'édition d'un compte.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * US0.1 (complément CRUD) — Mise à jour d'un compte.
     */
    public function update(RegisterRequest $request, User $user)
    {
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('users.index')->with('success', 'Compte mis à jour.');
    }

    /**
     * US0.1 (complément CRUD) — Suppression d'un compte.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Compte supprimé.');
    }
}
