<?php

// Wipe Heroes' Memories
// weaponx

// Setup : Twists equal to the number of players plus 4.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class WipeHeroesMemories_WeaponX extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = $this->setup->players + 4;
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
