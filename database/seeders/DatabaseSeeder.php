<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            OffreSeeder::class,
            CandidatSeeder::class,
            AnalyseSeeder::class,
        ]);
    }
}
