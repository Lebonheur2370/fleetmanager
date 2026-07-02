<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordChangeController extends Controller
{
    /**
     * Formulaire affiché obligatoirement au premier login d'un chauffeur
     * (identifiants générés automatiquement — cf. CredentialGeneratorService).
     */
    public function edit()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
            'doit_changer_mot_de_passe' => false,
        ]);

        return redirect()->route('dashboard')->with('success', "Mot de passe mis à jour.");
    }
}
