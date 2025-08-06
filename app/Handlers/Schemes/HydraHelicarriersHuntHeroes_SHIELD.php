<?php

// Hydra Helicarriers Hunt Heroes
// shield

// Setup : 8 Twists. Add an extra Hero.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class HydraHelicarriersHuntHeroes_SHIELD extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->heroes++;
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
