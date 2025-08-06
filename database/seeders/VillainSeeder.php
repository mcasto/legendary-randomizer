<?php

namespace Database\Seeders;

use App\Models\Villain;
use App\Models\VillainKeyword;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VillainSeeder extends Seeder
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
        $recs = GetSeedData::pull('villains');
        foreach ($recs as $rec) {
            $keywords = $this->findKeywords($rec);

            Villain::create([
                'id' => $rec['id'],
                'name' => $rec['name'],
                'set' => $rec['set']
            ]);

            foreach ($keywords as $keyword_id) {
                VillainKeyword::create([
                    'keyword_id' => $keyword_id,
                    'villain_id' => $rec['id']
                ]);
            }
        }
    }
}
