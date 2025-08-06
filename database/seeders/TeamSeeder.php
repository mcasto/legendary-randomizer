<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recs = GetSeedData::pull('teams');
        foreach ($recs as $rec) {
            if (!!$rec['value']) {
                $icon = file_get_contents(__DIR__ . '/icons/teams/' . $rec['value'] . '.svg');

                Team::create([
                    'id' => $rec['id'],
                    'value' => $rec['value'],
                    'label' => $rec['label'],
                    'icon' => $icon
                ]);
            }
        }
    }
}
