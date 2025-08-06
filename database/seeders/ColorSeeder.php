<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recs = GetSeedData::pull('colors');

        foreach ($recs as $rec) {
            if (!!$rec['value']) {
                $icon = file_get_contents(__DIR__ . '/icons/colors/' . $rec['value'] . '.svg');
                Color::create([
                    'id' => $rec['id'],
                    'value' => $rec['value'],
                    'label' => $rec['label'],
                    'icon' => $icon
                ]);
            }
        }
    }
}
