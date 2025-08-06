<?php

// Cursed Pages of the Darkhold Tome
// doctorstrange

// Setup : 11 Twists, representing Cursed Pages of the Darkhold Tome. Add an extra Villain Group.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class CursedPagesOfTheDarkholdTome_DoctorStrangeAndTheShadowsOfNightmare extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
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
