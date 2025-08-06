<?php

// Frame Heroes for Murder
// blackwidow

// Setup : 7 Twists. 6 Heroes.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class FrameHeroesForMurder_BlackWidow extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 7;
        $this->setup->heroes = 6;
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
