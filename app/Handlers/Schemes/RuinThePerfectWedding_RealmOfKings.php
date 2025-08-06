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

        // get wedding heroes
        $heroes = $this->es->getCandidate(entityType: 'heroes', take: 2);

        foreach ($heroes as $hero) {
            // remove candidate
            $this->es->removeCandidate($hero['id']);

            // add to heroes
            $this->es->addToDeck(entityType: 'heroes', entityId: $hero['id'], special: true);

            // add expectation
            $this->addExpectation(entityType: 'heroes', entityId: $hero['id']);

            // add to villains
            $this->es->addToDeck(entityType: 'heroes', section: 'villains', entityId: $hero['id'], special: true);

            // add expectation
            $this->addExpectation(entityType: 'heroes', section: 'villains', entityId: $hero['id']);
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
