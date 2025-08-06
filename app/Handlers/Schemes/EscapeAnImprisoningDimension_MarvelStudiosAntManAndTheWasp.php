<?php

// Escape an Imprisoning Dimension
// msaw

// Setup : 5 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class EscapeAnImprisoningDimension_MarvelStudiosAntManAndTheWasp extends BaseHandler
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
