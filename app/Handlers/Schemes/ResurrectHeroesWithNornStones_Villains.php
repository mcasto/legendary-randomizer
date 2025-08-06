<?php

// Resurrect Heroes with Norn Stones
// villains

// Setup : 8 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class ResurrectHeroesWithNornStones_Villains extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=8;
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
