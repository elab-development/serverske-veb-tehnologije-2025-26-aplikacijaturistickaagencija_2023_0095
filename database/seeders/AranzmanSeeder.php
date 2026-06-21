<?php

namespace Database\Seeders;

use App\Models\Aranzman;
use App\Models\Destinacija;
use App\Models\Prevoz;
use App\Models\Smestaj;
use Illuminate\Database\Seeder;

class AranzmanSeeder extends Seeder
{
    public function run(): void
    {
        $prevozi  = Prevoz::all();
        $smestaji = Smestaj::all();

        Destinacija::all()->each(function (Destinacija $destinacija) use ($prevozi, $smestaji) {
            Aranzman::factory()
                ->count(3)
                ->create([
                    'destinacija_id' => $destinacija->id,
                    'prevoz_id'      => $prevozi->isNotEmpty() ? $prevozi->random()->id : null,
                    'smestaj_id'     => $smestaji->isNotEmpty() ? $smestaji->random()->id : null,
                ]);
        });
    }
}
