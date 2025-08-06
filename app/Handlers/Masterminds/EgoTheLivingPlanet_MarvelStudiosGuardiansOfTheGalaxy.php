<?php

// Ego, the Living Planet
// msgotg

// Always Leads: Any Villain Group, plus add an additional Villain Group

namespace App\Handlers\Masterminds;

use App\Handlers\BaseHandler;
use App\Models\Mastermind;
use App\Models\Villain;
use App\Models\Henchmen;

class EgoTheLivingPlanet_MarvelStudiosGuardiansOfTheGalaxy extends BaseHandler
{
    /**
     * Handle Masterminds operations.
     */
    protected function handle(): void
    {
        // no always leads

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
