<?php

namespace Database\Factories;

use App\Models\Destinacija;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Destinacija>
 */
class DestinacijaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'naziv' => fake()->city(),
            'drzava' => fake()->country(),
            'grad' => fake()->city(),
            'opis' => fake()->paragraph(),
        ];
    }
}
