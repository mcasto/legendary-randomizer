<?php

// Televised Deathtraps of Mojoworld
// xmen

// Setup : 11 Twists. 6 Wounds per player in Wound Stack.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class TelevisedDeathtrapsOfMojoworld_XMen extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
        $this->setup->wounds = $this->setup->players * 6;
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
