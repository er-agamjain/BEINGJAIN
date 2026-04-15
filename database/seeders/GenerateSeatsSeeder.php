<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShowTiming;

class GenerateSeatsSeeder extends Seeder
{
    public function run(): void
    {
        $count = 0;
        ShowTiming::doesntHave('seats')->get()->each(function ($showTiming) use (&$count) {
            $showTiming->generateSeats();
            $count++;
        });

        $this->command->info("Generated seats for {$count} show timings.");
    }
}
