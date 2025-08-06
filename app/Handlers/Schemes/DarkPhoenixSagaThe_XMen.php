<?php

// Dark Phoenix Saga, The
// xmen

// Setup : 10 Twists. Include Hellfire Club as one of the Villain Groups. Add 14 Jean Grey Hero cards to the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class DarkPhoenixSagaThe_XMen extends BaseHandler
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
