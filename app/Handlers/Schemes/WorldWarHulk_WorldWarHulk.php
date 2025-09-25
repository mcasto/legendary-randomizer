<?php

// World War Hulk
// wwhulk

// Setup : 9 Twists. Put three additional Masterminds out of play, "Lurking." Each of the four Masterminds has two random Tactics.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class WorldWarHulk_WorldWarHulk extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 9;
        $this->setup->masterminds += 3;

        // get 3 mastermind candidates
        $candidates = $this->es->getCandidate(entityType: 'masterminds', take: 3);

        foreach ($candidates as $candidate) {
            // remove candidate
            $this->es->removeCandidate($candidate['id']);

            // add to deck
            $this->es->addToDeck(candidate: $candidate, special: true);

            // add expectation
            $this->addExpectation(candidate: $candidate);
        }
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
