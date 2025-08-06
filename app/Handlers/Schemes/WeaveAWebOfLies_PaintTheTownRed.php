<?php

// Weave a Web of Lies
// pttr

// Setup : 7 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class WeaveAWebOfLies_PaintTheTownRed extends BaseHandler
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
