<?php

// Secret Empire of Betrayal
// shield

// Setup : 11 Twists. Randomly pick 5 cards that cost 5 or less from an additional Hero. Shuffle them to form a "Dark Loyalty" deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class SecretEmpireOfBetrayal_SHIELD extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;

        // get additional hero
        $hero = $this->es->getCandidate(entityType: 'heroes');

        // remove candidate
        $this->es->removeCandidate($hero['id']);

        // add to deck
        $this->es->addToDeck(entityType: 'heroes', entityId: $hero['entity_id']);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $hero['entity_id']);
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
