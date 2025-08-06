<?php

// Crown Thor King of Asgard
// villains

// Setup : 8 Twists. Put the Thor Adversary next to this Plot.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class CrownThorKingOfAsgard_Villains extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
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
