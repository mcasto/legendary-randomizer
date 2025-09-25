<?php

// Master of Tyrants
// sw1

// Setup : 8 Twists. Choose 3 other Masterminds, and shuffle their 12 Tactics into the Villain Deck. Those Tactics are "Tyrant Villains" with their printed and no abilities.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Services\EntityService;

class MasterOfTyrants_SecretWarsVolume1 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->villains += 3;

        // get 3 masterminds from candidates
        $candidates = $this->es->getCandidate(entityType: 'masterminds', take: 3);

        // add them to deck & remove candidates
        foreach ($candidates as $candidate) {
            // add to deck
            $this->es->addToDeck(candidate: $candidate, section: 'villains', special: true);

            // add expectation
            $this->addExpectation(candidate: $candidate, section: 'villains');

            // remove from candidates
            $this->es->removeCandidate($candidate['id']);
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
