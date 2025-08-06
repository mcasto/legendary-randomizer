<?php

// War of the Frost Giants
// heroesofasgard

// Setup : 9 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class WarOfTheFrostGiants_HeroesOfAsgard extends BaseHandler
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
