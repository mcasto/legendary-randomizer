<?php

// Tornado of Terrigen Mists
// realmofkings

// Setup : 10 Twists. Each player puts a small object above the sewers to represent themself.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class TornadoOfTerrigenMists_RealmOfKings extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=10;
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
