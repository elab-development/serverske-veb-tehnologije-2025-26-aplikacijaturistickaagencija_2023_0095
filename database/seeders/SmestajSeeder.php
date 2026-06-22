<?php

namespace Database\Seeders;

use App\Models\PartnerSmestaja;
use App\Models\Smestaj;
use Illuminate\Database\Seeder;

class SmestajSeeder extends Seeder
{
    public function run(): void
    {
        PartnerSmestaja::all()->each(function (PartnerSmestaja $partner) {
            Smestaj::factory()->count(3)->create(['partner_smestaja_id' => $partner->id]);
        });
    }
}
