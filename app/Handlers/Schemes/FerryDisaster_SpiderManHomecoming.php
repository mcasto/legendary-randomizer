<?php

// Ferry Disaster
// spiderhomecoming

// Setup : 9 Twists. Put the Bystander Stack above the Sewers as the "Ferry."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class FerryDisaster_SpiderManHomecoming extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 9;
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
