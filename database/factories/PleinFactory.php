<?php

namespace Database\Factories;

use App\Models\Plein;
use App\Models\Vehicule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plein>
 */
class PleinFactory extends Factory
{
    protected $model = Plein::class;

    public function definition(): array
    {
        return [
            'vehicule_id' => Vehicule::factory(),
            'chauffeur_id' => null,
            'date_plein' => fake()->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
            'litres' => fake()->randomFloat(2, 20, 80),
            'montant' => fake()->randomFloat(0, 10000, 60000),
            'kilometrage' => fake()->numberBetween(0, 50000),
        ];
    }
}
