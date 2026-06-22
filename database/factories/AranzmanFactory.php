<?php

namespace Database\Factories;

use App\Models\Aranzman;
use App\Models\Destinacija;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Aranzman>
 */
class AranzmanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $datumPocetka = fake()->dateTimeBetween('+1 week', '+6 months');
        $datumZavrsetka = (clone $datumPocetka)->modify('+'.fake()->numberBetween(3, 14).' days');

        return [
            'destinacija_id' => Destinacija::factory(),
            'naziv' => fake()->sentence(3),
            'opis' => fake()->paragraph(),
            'cena' => fake()->numberBetween(100, 2000),
            'datum_pocetka' => $datumPocetka,
            'datum_zavrsetka' => $datumZavrsetka,
            'slobodna_mesta' => fake()->numberBetween(5, 50),
        ];
    }
}
