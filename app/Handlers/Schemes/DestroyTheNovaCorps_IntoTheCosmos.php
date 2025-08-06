<?php

// Destroy the Nova Corps
// intothecosmos

// Setup : 9 Twists. Exactly one Hero must be a Nova Hero. 1 player: 5 Heroes. Each player's starting deck adds 2 Wounds, 1 S.H.I.E.L.D. Officer, and a Nova card that costs 2.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;
use App\Services\EntityService;

class DestroyTheNovaCorps_IntoTheCosmos extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 9;

        // get nova hero
        $heroes = $this->es->pullCandidate(entityType: 'heroes', name: '\bNova\b', isRegex: true);
        $nova = $heroes[0];

        // add to deck
        $this->es->addToDeck(candidate: $nova);

        // add expectation
        $this->addExpectation(candidate: $nova);

        // remove candidates
        foreach ($heroes as $hero) {
            $hero->delete();
        }
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
