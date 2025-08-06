<?php

// Sniper Rifle Assassins
// blackwidow

// Setup : 11 Twists, minus 1 Twist per player.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class SniperRifleAssassins_BlackWidow extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11 - $this->setup->players;
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
