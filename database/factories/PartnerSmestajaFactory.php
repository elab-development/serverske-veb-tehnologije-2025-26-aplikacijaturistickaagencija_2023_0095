<?php

namespace Database\Factories;

use App\Models\PartnerSmestaja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PartnerSmestaja>
 */
class PartnerSmestajaFactory extends Factory
{
    protected $model = PartnerSmestaja::class;

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
