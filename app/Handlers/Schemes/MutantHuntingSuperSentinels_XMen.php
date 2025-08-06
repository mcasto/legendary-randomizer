<?php

// Mutant-Hunting Super Sentinels
// xmen

// Setup : 9 Twists. Include 10 Sentinels as extra Henchmen (or substitute another Henchman group).

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Services\EntityService;

class MutantHuntingSuperSentinels_XMen extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 9;
        $this->setup->henchmen++;

        // get an extra henchmen
        $candidate = $this->es->getCandidate(entityType: 'henchmen');

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to deck
        $this->es->addToDeck(entityType: 'henchmen', entityId: $candidate['id'], special: true);

        // add expectation
        $this->addExpectation(entityType: 'henchmen', entityId: $candidate['id']);
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
