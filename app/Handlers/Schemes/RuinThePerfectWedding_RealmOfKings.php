<?php

// Ruin the Perfect Wedding
// realmofkings

// Setup : 11 Twists. Set aside two extra Heroes to get married. Prepare each Wedding Hero into a seperate 14-card stack, ordered by cost with the lowest cost on top.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class RuinThePerfectWedding_RealmOfKings extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
        $this->setup->heroes += 2;
        $this->setup->villains += 2;

        // get wedding heroes
        $heroes = $this->es->getCandidate(entityType: 'heroes', take: 2);

        foreach ($heroes as $candidate) {
            // remove candidate
            $this->es->removeCandidate($candidate['id']);

            // add to heroes
            $this->es->addToDeck(candidate: $candidate, special: true);

            // add expectation
            $this->addExpectation(candidate: $candidate);

            // add to villains
            $this->es->addToDeck(candidate: $candidate, section: 'villains', special: true);

            // add expectation
            $this->addExpectation(candidate: $candidate, section: 'villains',);
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
