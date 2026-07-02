<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Crée le premier compte administrateur.
     * ⚠️ À changer immédiatement après le premier déploiement / la soutenance.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@fleetmanager.ga'],
            [
                'name' => 'Administrateur FleetManager',
                'password' => Hash::make('ChangeMoi2026!'),
                'role' => 'admin',
                'doit_changer_mot_de_passe' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
