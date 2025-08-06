<?php

// Imprison Unregistered Superhumans
// civilwar

// Setup : 11 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class ImprisonUnregisteredSuperhumans_CivilWar extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=11;
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
