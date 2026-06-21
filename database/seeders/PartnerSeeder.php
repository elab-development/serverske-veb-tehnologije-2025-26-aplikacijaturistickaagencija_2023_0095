<?php

namespace Database\Seeders;

use App\Models\PartnerPrevoza;
use App\Models\PartnerSmestaja;
use App\Models\User;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(3)->create(['role' => 'partner_prevoz'])->each(function (User $user) {
            PartnerPrevoza::factory()->create(['user_id' => $user->id]);
        });

        User::factory()->count(3)->create(['role' => 'partner_smestaj'])->each(function (User $user) {
            PartnerSmestaja::factory()->create(['user_id' => $user->id]);
        });
    }
}
