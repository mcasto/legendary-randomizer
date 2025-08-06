<?php

// Alien Brood Encounters
// xmen

// Setup : 8 Twists. Add 10 Brood as extra Henchmen. No Bystanders in Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Candidate;
use App\Models\Henchmen;
use App\Services\EntityService;

class AlienBroodEncounters_XMen extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->bystanders = 0;
        $this->setup->henchmen++;

        $brood = $this->es->pullCandidate(entityType: 'henchmen', name: 'Brood, The');

        // add to deck
        $this->es->addToDeck($brood);

        // add expectation
        $this->addExpectation($brood);

        // remove candidate
        $brood->delete();
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
