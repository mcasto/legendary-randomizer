<?php

// Trapped in the Insane Asylum
// newmutants

// Setup : 1 Twist, plus 2 Twists per player.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class TrappedInTheInsaneAsylum_TheNewMutants extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 1 + $this->setup->players * 2;
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
