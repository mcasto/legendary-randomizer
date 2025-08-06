<?php

namespace Database\Seeders;

use App\Models\Scheme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recs = GetSeedData::pull('schemes');

        foreach ($recs as $rec) {
            $veiled = $rec['cards'][0]['veiled'] ?? false;
            $unveiled = $rec['cards'][0]['unveiled'] ?? false;

            Scheme::create([
                'id' => $rec['id'],
                'name' => $rec['name'],
                'set' => $rec['set'],
                'veiled' => $veiled,
                'unveiled' => $unveiled
            ]);
        }
    }
}
