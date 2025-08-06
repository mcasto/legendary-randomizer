<?php

// Transform Commuters into Giant Ants
// antman

// Setup : Twists equal to the number of players plus 6.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class TransformCommutersIntoGiantAnts_AntMan extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = $this->setup->players + 6;
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
