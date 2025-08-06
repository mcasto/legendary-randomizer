<?php

namespace Database\Seeders;

use App\Models\HeroColor;
use App\Models\HeroTeam;
use Illuminate\Database\Seeder;

class HeroTeamSeeder extends Seeder
{
    private $teams = [];

    private function parseCards($entity): void
    {
        if (isset($entity['team'])) {
            $this->teams[] = $entity['team'];
        }

        foreach ($entity['cards'] as $card) {
            // $this->parseAbilities($card, $entity['name'] == 'Patriot');
            if (isset($card['team'])) {
                $this->teams[] = $card['team'];
            }
        }
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recs = GetSeedData::pull('heroes');
        foreach ($recs as $hero) {
            $this->teams = [];
            $this->parseCards($hero);
            $teams = array_unique($this->teams);
            $teams = array_values($teams);

            foreach ($teams as $team) {
                $rec = [
                    'hero_id' => $hero['id'],
                    'team_id' => $team
                ];

                HeroTeam::create($rec);
            }
        }
    }
}
