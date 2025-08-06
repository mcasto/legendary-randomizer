<?php

// Pull Earth into Medieval Times
// antman

// Setup : 9 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class PullEarthIntoMedievalTimes_AntMan extends BaseHandler
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
