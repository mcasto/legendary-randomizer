<?php

// Everybody Hates Deadpool
// deadpool

// Setup : 6 Twists. Use at least 1 Hero.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;
use App\Services\EntityService;

class EverybodyHatesDeadpool_Deadpool extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 6;

        // get deadpool
        $deadpool = $this->es->pullCandidate(entityType: 'heroes', name: '\bDeadpool\b', isRegex: true, take: 1);

        // add to deck
        $this->es->addToDeck(candidate: $deadpool);

        // add expectation
        $this->addExpectation(candidate: $deadpool);

        // remove candidate
        $deadpool->delete();
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
