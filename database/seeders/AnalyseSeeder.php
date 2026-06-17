<?php

namespace Database\Seeders;

use App\Models\Analyse;
use Illuminate\Database\Seeder;

class AnalyseSeeder extends Seeder
{
    public function run(): void
    {
        Analyse::factory()->count(3)->completed()->create();
        Analyse::factory()->count(2)->create();
        Analyse::factory()->count(1)->failed()->create();
    }
}
