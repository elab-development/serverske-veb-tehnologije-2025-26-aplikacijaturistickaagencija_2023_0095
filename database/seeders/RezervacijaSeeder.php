<?php

namespace Database\Seeders;

use App\Models\Aranzman;
use App\Models\Rezervacija;
use App\Models\User;
use Illuminate\Database\Seeder;

class RezervacijaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $korisnici = User::all();
        $aranzmani = Aranzman::all();

        $aranzmani->each(function (Aranzman $aranzman) use ($korisnici) {
            Rezervacija::factory()
                ->count(2)
                ->create([
                    'korisnik_id' => $korisnici->random()->id,
                    'aranzman_id' => $aranzman->id,
                ]);
        });
    }
}
