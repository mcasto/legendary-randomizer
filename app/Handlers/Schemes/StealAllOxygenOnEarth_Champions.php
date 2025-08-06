<?php

// Steal All Oxygen on Earth
// champions

// Setup : 8 Twists. The "Oxygen Level" starts at 8.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class StealAllOxygenOnEarth_Champions extends BaseHandler
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
