<?php

// Age of Ultron
// antman

// Setup : 11 Twists. 4-5 Players: Add another Hero.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class AgeOfUltron_AntMan extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
        if ($this->setup->players > 3) {
            $this->setup->heroes++;
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
