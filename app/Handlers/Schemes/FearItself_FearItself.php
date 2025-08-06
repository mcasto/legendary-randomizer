<?php

// Fear Itself
// fearitself

// Setup : 10 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class FearItself_FearItself extends BaseHandler
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
