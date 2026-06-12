<?php

namespace Database\Seeders;

use App\Models\Aranzman;
use App\Models\Destinacija;
use Illuminate\Database\Seeder;

class AranzmanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Destinacija::all()->each(function (Destinacija $destinacija) {
            Aranzman::factory()
                ->count(3)
                ->create(['destinacija_id' => $destinacija->id]);
        });
    }
}
