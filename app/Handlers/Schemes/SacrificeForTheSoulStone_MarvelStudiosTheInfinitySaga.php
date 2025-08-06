<?php

// Sacrifice for the Soul Stone
// msis

// Setup : Twists equal to the number of players plus 4.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class SacrificeForTheSoulStone_MarvelStudiosTheInfinitySaga extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = $this->setup->players + 4;
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
