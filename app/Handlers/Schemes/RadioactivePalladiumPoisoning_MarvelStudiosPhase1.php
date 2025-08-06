<?php

// Radioactive Palladium Poisoning
// marvelstudios

// Setup : 8 Twists. Wound stack holds 6 Wounds per player.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class RadioactivePalladiumPoisoning_MarvelStudiosPhase1 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
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
