<?php

namespace Database\Factories;

use App\Models\Entretien;
use App\Models\Vehicule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Entretien>
 */
class EntretienFactory extends Factory
{
    protected $model = Entretien::class;

    public function definition(): array
    {
        return [
            'vehicule_id' => Vehicule::factory(),
            'type' => fake()->randomElement(['vidange', 'revision', 'pneus', 'freins', 'autre']),
            'date_entretien' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'kilometrage' => fake()->numberBetween(0, 50000),
            'cout' => fake()->randomFloat(0, 5000, 100000),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
