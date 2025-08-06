<?php

namespace Database\Seeders;

use App\Models\Henchmen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HenchmenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recs = GetSeedData::pull('henchmen');
        foreach ($recs as $rec) {
            Henchmen::create([
                'id' => $rec['id'],
                'name' => $rec['name'],
                'set' => $rec['set']
            ]);
        }
    }
}
