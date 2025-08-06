<?php

// Fountain of Eternal Life, The
// sw2

// Setup : 8 Twists. (1 player: 4 Twists.)

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class FountainOfEternalLifeThe_SecretWarsVolume2 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = $this->setup->players == 1 ? 4 : 8;
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
