<?php

// Five Families of Crime
// noir

// Setup : 8 Twists. Add two extra Villain Groups. Split the Villain Deck into 5 shuffled decks, one above each city space.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class FiveFamiliesOfCrime_Noir extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->villains += 2;
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
