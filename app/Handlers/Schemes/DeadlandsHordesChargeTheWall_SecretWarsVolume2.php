<?php

// Deadlands Hordes Charge the Wall
// sw2

// Setup : 8 Twists. Add an extra Villain Group.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class DeadlandsHordesChargeTheWall_SecretWarsVolume2 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->villains++;
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
