<?php

// Armada of Kang
// msaw

// Choose an unused Henchman Group and stack Henchmen from it next to this Scheme equal to the number of players.

namespace App\Handlers\Villains;

use App\Handlers\BaseHandler;

class ArmadaOfKang_MarvelStudiosAntManAndTheWasp extends BaseHandler
{
    /**
     * Handle Villains operations.
     */
    protected function handle(): void
    {
        $this->setup->henchmen++;

        $hench = $this->es->getCandidate(entityType: 'henchmen');

        // remove candidate
        $this->es->removeCandidate($hench->id);

        // add to deck
        $this->es->addToDeck($hench, special: true);

        // add expectation
        $this->addExpectation($hench);
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
