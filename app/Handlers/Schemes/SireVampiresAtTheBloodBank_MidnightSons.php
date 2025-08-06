<?php

// Sire Vampires at the Blood Bank
// midnightsons

// Setup : 10 Twists. Add an extra Henchman Group of 10 cards as "Vampire Neonates". Put this Scheme above the Bank to mark as the "Blood Bank."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class SireVampiresAtTheBloodBank_MidnightSons extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 10;
        $this->setup->henchmen++;

        // get candidate
        $vampires = $this->es->getCandidate(entityType: 'henchmen');

        // remove candidate
        $this->es->removeCandidate($vampires['id']);

        // add to deck
        $this->es->addToDeck(entityType: 'henchmen', entityId: $vampires['id'], special: true);

        // add expectation
        $this->addExpectation(entityType: 'henchmen', entityId: $vampires['id']);
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
