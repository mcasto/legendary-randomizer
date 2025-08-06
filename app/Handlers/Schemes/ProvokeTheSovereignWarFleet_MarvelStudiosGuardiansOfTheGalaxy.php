<?php

// Provoke the Sovereign War Fleet
// msgotg

// Setup : 11 Twists. Add an extra Villain Group.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class ProvokeTheSovereignWarFleet_MarvelStudiosGuardiansOfTheGalaxy extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
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
