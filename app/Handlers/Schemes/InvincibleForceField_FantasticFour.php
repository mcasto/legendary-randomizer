<?php

// Invincible Force Field
// ff

// Setup : 7 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class InvincibleForceField_FantasticFour extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=7;
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
