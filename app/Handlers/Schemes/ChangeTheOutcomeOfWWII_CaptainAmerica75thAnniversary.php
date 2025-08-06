<?php

// Change the Outcome of WWII
// captainamerica

// Setup : 7 Twists. Add an extra Villain Group.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class ChangeTheOutcomeOfWWII_CaptainAmerica75thAnniversary extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 7;
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
