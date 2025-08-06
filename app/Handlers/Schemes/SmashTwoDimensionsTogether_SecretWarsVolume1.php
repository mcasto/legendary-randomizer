<?php

// Smash Two Dimensions Together
// sw1

// Setup : 8 Twists. Add an extra Villain Group. Put the Villain Deck on the Bank space.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class SmashTwoDimensionsTogether_SecretWarsVolume1 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->villains++;
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
