<?php

// Deadpool Wants a Chimichanga
// deadpool

// Setup : 6 Twists. 12 total Bystanders in the Villain Deck. All Bystanders represent "Chimichangas." (They're Bystanders too.) 3-5 players: Add a Villain Group.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class DeadpoolWantsAChimichanga_Deadpool extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 6;
        $this->setup->bystanders = 12;
        if ($this->setup->players > 2) {
            $this->setup->villains++;
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
