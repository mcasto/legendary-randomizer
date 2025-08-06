<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\HeroTeam;
use Illuminate\Support\Collection;

class TeamService
{
    /**
     * Get teams with minimum amount of heroes
     */
    public static function getTeams($setup, $minCount)
    {
        // get candidate heroes with their team relationships
        $candidateHeroes = Candidate::where('setup_id', $setup->id)
            ->where('entity_type', 'heroes')
            ->with(['entity.hero_teams'])
            ->inRandomOrder()
            ->get();

        // get teams from heroes
        $teams = [];
        foreach ($candidateHeroes as $candidate) {
            $hero = $candidate->entity;
            foreach ($hero->hero_teams as $ht) {
                $teams[] = $ht->team_id;
            }
        }
        $teams = array_unique($teams);
        shuffle($teams);

        // get teams with minCount heroes
        foreach ($teams as $team) {
            $ht = HeroTeam::where('team_id', $team)
                ->get();

            dump(['team_id' => $team, 'count' => $ht->count()]);
        }

        return $teams;
    }
}
