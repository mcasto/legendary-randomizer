<?php

// Hypnotize Every Human
// champions

// Setup : 8 Twists. Add another Henchman Villain Group. No Bystanders in the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class HypnotizeEveryHuman_Champions extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->henchmen++;
        $this->setup->bystanders = 0;
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
