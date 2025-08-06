<?php

namespace Database\Seeders;

use App\Models\Hero;
use App\Models\HeroKeyword;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroSeeder extends Seeder
{
    private function findKeywords($rec)
    {
        preg_match_all("/\{\"keyword\":([0-9]+)/", json_encode($rec), $kl);
        return $kl[1] ?? [];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recs = GetSeedData::pull('heroes');
        foreach ($recs as $rec) {
            $keywords = $this->findKeywords($rec);
            Hero::create([
                'id' => $rec['id'],
                'name' => $rec['name'],
                'set' => $rec['set']
            ]);

            foreach ($keywords as $keyword_id) {
                HeroKeyword::create([
                    'keyword_id' => $keyword_id,
                    'hero_id' => $rec['id']
                ]);
            }
        }
    }
}
