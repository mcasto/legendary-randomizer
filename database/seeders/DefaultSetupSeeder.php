<?php

namespace Database\Seeders;

use App\Models\DefaultSetup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recs = GetSeedData::defaultSetups();
        foreach ($recs as $rec) {
            DefaultSetup::create($rec);
        }
    }
}
