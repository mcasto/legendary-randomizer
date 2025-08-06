<?php

// Bastion, Fused Sentinel
// messiahcomplex

// Always Leads: Purifiers and any Sentinel Henchmen Group.

namespace App\Handlers\Masterminds;

use App\Handlers\BaseHandler;
use App\Models\Mastermind;
use App\Models\Villain;
use App\Models\Henchmen;

class BastionFusedSentinel_MessiahComplex extends BaseHandler
{
    /**
     * Handle Masterminds operations.
     */
    protected function handle(): void
    {
        if ($this->setup->players == 1) {
            // ignore always leads
            return;
        }

        // get purifiers
        $purifiers = $this->es->pullCandidate(entityType: 'villains', name: 'Purifiers', take: 1);

        // add to deck
        $this->es->addToDeck($purifiers);

        // add expectation
        $this->addExpectation($purifiers);

        // remove candidate
        $purifiers->delete();

        // get sentinel
        $sentinel = $this->es->pullCandidate(entityType: 'henchmen', name: '\bSentinel\b', isRegex: true, take: 1);

        // add to deck
        $this->es->addToDeck($sentinel);

        // add expectation
        $this->addExpectation($sentinel);

        // remove candidate
        $sentinel->delete();
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
