<?php

// Last Stand at Avengers Tower
// fearitself

// Setup : 6 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class LastStandAtAvengersTower_FearItself extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=6;
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
