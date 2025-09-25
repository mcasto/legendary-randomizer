<?php

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Candidate;
use App\Models\Hero;
use App\Models\Team;
use App\Services\EntityService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AvengersVsXMen_CivilWar extends BaseHandler
{
    protected function handle(): void
    {
        $this->setup->twists = 9;
        $this->setup->heroes = 6;

        // get teams with 3+ heroes
        $teams = Team::getTeamsWithAvailableHeroes(setup_id: $this->setup->id, numHeroes: 3)->take(2);

        foreach ($teams as $team) {
            $heroes = $team['available_heroes']->take(3);

            foreach ($heroes as $hero) {
                $candidate = $this->es->getCandidate(entityType: 'heroes', entityId: $hero['id']);

                // add hero to deck
                $this->es->addToDeck(candidate: $candidate);

                // add to expectations
                $this->addExpectation(candidate: $candidate);
            }
        }
    }
}
