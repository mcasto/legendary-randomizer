<?php

// Unleash the Abilisk Space Monster
// msgotg

// Setup : 9 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class UnleashTheAbiliskSpaceMonster_MarvelStudiosGuardiansOfTheGalaxy extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=9;
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
