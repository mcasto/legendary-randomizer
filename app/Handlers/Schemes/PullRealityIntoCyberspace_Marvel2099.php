<?php

// Pull Reality Into Cyberspace
// 2099

// Setup : 7 Twists, representing "Cyberspace."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class PullRealityIntoCyberspace_Marvel2099 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 7;
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
