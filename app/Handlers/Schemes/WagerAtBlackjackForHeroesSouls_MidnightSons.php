<?php

// Wager at Blackjack for Heroes' Souls
// midnightsons

// Setup : 11 Twists. And two extra Heroes.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class WagerAtBlackjackForHeroesSouls_MidnightSons extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
        $this->setup->heroes += 2;
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
