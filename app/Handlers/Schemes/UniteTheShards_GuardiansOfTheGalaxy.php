<?php

// Unite the Shards
// gotg

// Setup : 30 in the supply. Twists equal to the number of players plus 5.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class UniteTheShards_GuardiansOfTheGalaxy extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = $this->setup->players + 5;
        $this->setup->shards = 30;
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
