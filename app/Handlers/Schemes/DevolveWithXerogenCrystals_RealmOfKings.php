<?php

// Devolve with Xerogen Crystals
// realmofkings

// Setup : Add Twists equal to the number of players plus 3. Add an extra Henchman Group of 10 cards as "Xerogen Experiments."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Services\EntityService;

class DevolveWithXerogenCrystals_RealmOfKings extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = $this->setup->players + 3;
        $this->setup->henchmen++;

        // get henchmen candidate
        $candidate = $this->es->getCandidate(entityType: 'henchmen');

        // add to deck
        $this->es->addToDeck(candidate: $candidate, special: true);

        // add expectation
        $this->addExpectation(candidate: $candidate);

        // remove candidate
        $candidate->delete();
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
