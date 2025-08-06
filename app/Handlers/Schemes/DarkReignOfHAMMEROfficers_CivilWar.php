<?php

// Dark Reign of H.A.M.M.E.R. Officers
// civilwar

// Setup : 7 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class DarkReignOfHAMMEROfficers_CivilWar extends BaseHandler
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
