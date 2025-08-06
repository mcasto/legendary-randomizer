<?php

// Halve All Life in the Universe
// msis

// Setup : 5 Twists

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class HalveAllLifeInTheUniverse_MarvelStudiosTheInfinitySaga extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=5;
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
