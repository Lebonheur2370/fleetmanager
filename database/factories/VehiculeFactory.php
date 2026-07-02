<?php

namespace Database\Factories;

use App\Models\Vehicule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicule>
 */
class VehiculeFactory extends Factory
{
    protected $model = Vehicule::class;

    public function definition(): array
    {
        return [
            'immatriculation' => strtoupper(fake()->unique()->bothify('###-???-##')),
            'marque' => fake()->randomElement(['Toyota', 'Nissan', 'Hyundai', 'Renault']),
            'modele' => fake()->randomElement(['Hilux', 'Patrol', 'Tucson', 'Duster']),
            'annee' => fake()->numberBetween(2015, 2026),
            'kilometrage' => fake()->numberBetween(0, 50000),
            'statut' => 'disponible',
        ];
    }
}
