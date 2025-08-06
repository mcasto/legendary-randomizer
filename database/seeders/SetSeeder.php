<?php

namespace Database\Seeders;

use App\Models\Set;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recs = GetSeedData::pull('sets');
        foreach ($recs as $rec) {
            Set::create([
                'id' => $rec['id'],
                'value' => $rec['value'],
                'label' => $rec['label']
            ]);
        }
    }
}
