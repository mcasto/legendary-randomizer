<?php

// Crush Them With My Bare Hands
// sw1

// Setup : 5 Twists. If playing solo, add an extra Villain Group.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class CrushThemWithMyBareHands_SecretWarsVolume1 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 5;
        if ($this->setup->players == 1) {
            $this->setup->villains++;
        }
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
