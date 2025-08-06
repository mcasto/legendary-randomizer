<?php

// Portals to the Dark Dimension
// coreset

// Setup : 7 Twists. Each Twist is a Dark Portal.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class PortalsToTheDarkDimension_CoreSet extends BaseHandler
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
