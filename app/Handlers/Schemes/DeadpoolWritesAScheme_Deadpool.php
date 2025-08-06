<?php

// Deadpool Writes a Scheme
// deadpool

// Setup : Hey, writing these doesn't seem so tough. Use the best Hero in the game: Deadpool! Add 6 Twists of Lemon, shake vigorously, and I'll make it up as I go.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;
use App\Services\EntityService;

class DeadpoolWritesAScheme_Deadpool extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 6;

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
