<?php

namespace Database\Factories;

use App\Models\PartnerSmestaja;
use App\Models\Smestaj;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Smestaj>
 */
class SmestajFactory extends Factory
{
    protected $model = Smestaj::class;

    public function definition(): array
    {
        return [
            'partner_smestaja_id' => PartnerSmestaja::factory(),
            'naziv'               => fake()->company(),
            'tip'                 => fake()->randomElement(['hotel', 'hostel', 'apartman', 'villa', 'kamp']),
            'adresa'              => fake()->address(),
            'broj_zvezdica'       => fake()->numberBetween(1, 5),
        ];
    }
}
