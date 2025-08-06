<?php

// Befoul Earth Into a Polluted Wasteland
// 2099

// Setup : Add an extra Hero. 8 Twists, representing "Toxic Sludge."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class BefoulEarthIntoAPollutedWasteland_Marvel2099 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->heroes++;
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
