<?php

// Steal the Weaponized Plutonium
// darkcity

// Setup : 8 Twists representing Plutonium. Add an extra Villain Group.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class StealTheWeaponizedPlutonium_DarkCity extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->villains++;
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
