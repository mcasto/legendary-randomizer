<?php

// Invasion of the Venom Symbiotes
// venom

// Setup : 8 Twists. Add an extra Henchman Group.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class InvasionOfTheVenomSymbiotes_Venom extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->henchmen++;
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
