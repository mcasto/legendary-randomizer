<?php

// Subjugate with Obedience Disks
// wwhulk

// Setup : 11 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class SubjugateWithObedienceDisks_WorldWarHulk extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=11;
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
