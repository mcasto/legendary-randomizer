<?php

// Korvac Saga, The
// revelations

// Setup : 8 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class KorvacSagaThe_Revelations extends BaseHandler
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
