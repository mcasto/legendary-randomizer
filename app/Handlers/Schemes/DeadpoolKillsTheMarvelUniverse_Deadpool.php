<?php

// Deadpool Kills the Marvel Universe
// deadpool

// Setup : Use Deadpool as one of the Heroes. 2 players: Use 4 Heroes total. 1-3 players: 6 Twists. 4-5 Players: 5 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Candidate;
use App\Models\Hero;
use App\Services\EntityService;

class DeadpoolKillsTheMarvelUniverse_Deadpool extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        if ($this->setup->players == 2) {
            $this->setup->heroes = 4;
        }

        $this->setup->twists = $this->setup->players < 4 ? 6 : 5;

        $deadpool = $this->es->pullCandidate(entityType: 'heroes', name: '\bDeadpool\b', isRegex: true, take: 1);

        // add to deck
        $this->es->addToDeck(candidate: (object) $deadpool);

        // add expectation
        $this->addExpectation(candidate: (object) $deadpool);

        // remove from candidates
        $this->es->removeCandidate($deadpool['id']);
    }
}

/*
    setup:
        players
        twists
        schemes
        masterminds
        villains
        henchmen
        heroes
        bystanders
        wounds
        officers
        shards
*/
