<?php

// Poison Lakes with Nanite Microbots
// blackpanther

// Setup : Twists equal to 5 plus the number of players. 30 Wounds in the Wound Stack.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class PoisonLakesWithNaniteMicrobots_BlackPanther extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = -5 + $this->setup->players;
        $this->setup->wounds = 30;
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
