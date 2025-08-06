<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\HeroColor;
use Illuminate\Database\Seeder;

class HeroColorSeeder extends Seeder
{
    private $colors = [];

    private function incColorCounter($hc): void
    {
        if (!isset($this->colors[$hc])) {
            $this->colors[$hc] = 1;
        } else {
            $this->colors[$hc]++;
        }
    }

    private function parseCards($entity): array
    {
        foreach ($entity['cards'] as $card) {
            if (isset($card['hc'])) {
                $this->incColorCounter($card['hc']);
            }
            if (isset($card['hc2'])) {
                $this->incColorCounter($card['hc2']);
            }
        }

        arsort($this->colors);

        return array_keys($this->colors);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recs = GetSeedData::pull('heroes');
        foreach ($recs as $hero) {
            $this->colors = [];

            $colors = $this->parseCards($hero);

            foreach ($colors as $color) {
                $rec = [
                    'hero_id' => $hero['id'],
                    'color_id' => $color
                ];

                HeroColor::create($rec);
            }
        }
    }
}
