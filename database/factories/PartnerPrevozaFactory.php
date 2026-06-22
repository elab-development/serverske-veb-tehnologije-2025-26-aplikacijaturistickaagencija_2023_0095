<?php

namespace Database\Factories;

use App\Models\PartnerPrevoza;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PartnerPrevoza>
 */
class PartnerPrevozaFactory extends Factory
{
    protected $model = PartnerPrevoza::class;

    public function definition(): array
    {
        return [
            'user_id'          => null,
            'naziv'            => fake()->company(),
            'kontakt_email'    => fake()->companyEmail(),
            'kontakt_telefon'  => fake()->phoneNumber(),
        ];
    }
}
