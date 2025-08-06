<?php

// Capture Baby Hope
// darkcity

// Setup : 8 Twists. Put a token on this Scheme to represent the baby, Hope Summers.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class CaptureBabyHope_DarkCity extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=8;
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
