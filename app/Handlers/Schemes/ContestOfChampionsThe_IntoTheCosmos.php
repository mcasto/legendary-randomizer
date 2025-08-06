<?php

// Contest of Champions, The
// intothecosmos

// Setup : 11 Twists. Add an extra Hero. Put 11 random cards from the Hero Deck face up in a "Contest Row."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class ContestOfChampionsThe_IntoTheCosmos extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
        $this->setup->heroes++;
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
