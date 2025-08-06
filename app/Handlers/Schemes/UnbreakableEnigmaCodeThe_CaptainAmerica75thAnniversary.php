<?php

// Unbreakable Enigma Code, The
// captainamerica

// Setup : 6 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class UnbreakableEnigmaCodeThe_CaptainAmerica75thAnniversary extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=6;
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
