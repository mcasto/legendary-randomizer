<?php

// Hack Cerebro Servers To...
// messiahcomplex

// Setup : 10 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Handlers\Traits\VeiledUnveiledSchemeTrait;

class HackCerebroServersTo_MessiahComplex extends BaseHandler
{
    use VeiledUnveiledSchemeTrait;

    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        // Handle scheme setup (twists count, scheme increment)
        $this->handleSchemeSetup(10);

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
