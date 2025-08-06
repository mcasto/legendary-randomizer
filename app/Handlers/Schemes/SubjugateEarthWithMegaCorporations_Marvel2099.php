<?php

// Subjugate Earth with Mega-Corporations
// 2099

// Setup : Add an extra Hero. 11 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class SubjugateEarthWithMegaCorporations_Marvel2099 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
        $this->setup->heroes++;
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
