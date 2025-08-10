<?php

// ...Unleash an Anti-Mutant Bioweapon
// messiahcomplex

//

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Handlers\Traits\VeiledUnveiledSchemeTrait;

class UnleashAnAntiMutantBioweapon_MessiahComplex extends BaseHandler
{
    use VeiledUnveiledSchemeTrait;

    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        // Handle scheme setup (no specific twists for unveiled schemes)
        $this->handleSchemeSetup(0);

        $this->setup->twists = -1;

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
