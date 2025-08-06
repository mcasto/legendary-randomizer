<?php

// Annihilation: Conquest
// intothecosmos

// Setup : 11 Twists. Add an extra Hero.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class AnnihilationConquest_IntoTheCosmos extends BaseHandler
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
