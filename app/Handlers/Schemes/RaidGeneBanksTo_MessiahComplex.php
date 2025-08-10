<?php

// Raid Gene Banks To...
// messiahcomplex

// Setup : 8 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Handlers\Traits\VeiledUnveiledSchemeTrait;

class RaidGeneBanksTo_MessiahComplex extends BaseHandler
{
    use VeiledUnveiledSchemeTrait;

    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        // Handle scheme setup (twists count, scheme increment)
        $this->handleSchemeSetup(8);

        // Handle veiled/unveiled scheme pairing
        $this->handleVeiledUnveiledPairing();
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
