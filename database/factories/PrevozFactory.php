<?php

namespace Database\Factories;

use App\Models\PartnerPrevoza;
use App\Models\Prevoz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prevoz>
 */
class PrevozFactory extends Factory
{
    protected $model = Prevoz::class;

    public function definition(): array
    {
        return [
            'partner_prevoza_id' => PartnerPrevoza::factory(),
            'naziv'              => fake()->sentence(3),
            'tip'                => fake()->randomElement(['avion', 'autobus', 'brod', 'voz']),
            'polaziste'          => fake()->city(),
            'odrediste'          => fake()->city(),
        ];
    }
}
