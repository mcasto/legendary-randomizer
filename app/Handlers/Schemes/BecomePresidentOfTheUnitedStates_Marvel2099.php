<?php

// Become President of the United States
// 2099

// Setup : 11 Twists

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class BecomePresidentOfTheUnitedStates_Marvel2099 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=11;
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
