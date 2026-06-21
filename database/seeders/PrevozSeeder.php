<?php

namespace Database\Seeders;

use App\Models\PartnerPrevoza;
use App\Models\Prevoz;
use Illuminate\Database\Seeder;

class PrevozSeeder extends Seeder
{
    public function run(): void
    {
        PartnerPrevoza::all()->each(function (PartnerPrevoza $partner) {
            Prevoz::factory()->count(3)->create(['partner_prevoza_id' => $partner->id]);
        });
    }
}
