<?php

// Break the Planet Asunder
// wwhulk

// Setup : 9 Twists. 7 Heroes.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class BreakThePlanetAsunder_WorldWarHulk extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 9;
        $this->setup->heroes = 7;
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
