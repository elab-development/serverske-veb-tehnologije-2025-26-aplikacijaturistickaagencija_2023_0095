<?php

namespace Database\Factories;

use App\Models\Aranzman;
use App\Models\Rezervacija;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rezervacija>
 */
class RezervacijaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brojOsoba = fake()->numberBetween(1, 5);

        return [
            'korisnik_id' => User::factory(),
            'aranzman_id' => Aranzman::factory(),
            'broj_osoba' => $brojOsoba,
            'ukupna_cena' => fake()->numberBetween(100, 2000) * $brojOsoba,
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled']),
        ];
    }
}
