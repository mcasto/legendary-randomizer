<?php

// God-Emperor of Battleworld, The
// sw2

// Setup : 8 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class GodEmperorOfBattleworldThe_SecretWarsVolume2 extends BaseHandler
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
