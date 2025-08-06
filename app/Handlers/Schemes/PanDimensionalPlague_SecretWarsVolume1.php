<?php

// Pan-Dimensional Plague
// sw1

// Setup : 10 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class PanDimensionalPlague_SecretWarsVolume1 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=10;
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
