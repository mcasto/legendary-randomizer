<?php

// Horror of Horrors
// xmen

// Setup : 6 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class HorrorOfHorrors_XMen extends BaseHandler
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
