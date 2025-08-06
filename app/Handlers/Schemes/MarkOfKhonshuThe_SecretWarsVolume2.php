<?php

// Mark of Khonshu, The
// sw2

// Setup : 10 Twists. Always include Khonshu Guardians. Add all fourteen cards for an extra Hero to the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Henchmen;
use App\Services\EntityService;

class MarkOfKhonshuThe_SecretWarsVolume2 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 10;
        $this->setup->heroes++;
        $this->setup->villains++;

        $guardians = Henchmen::where('name', 'Khonshu Guardians')->first();
        $candidate = $this->es->getCandidate(entityType: 'henchmen', entityId: $guardians->id);

        $hero = $this->es->getCandidate(entityType: 'heroes');

        // add guardians to deck
        $this->es->addToDeck(entityType: 'henchmen', entityId: $guardians->id);

        // add expectation
        $this->addExpectation(entityType: 'henchmen', entityId: $guardians->id);

        // remove guardians from candidates
        $this->es->removeCandidate($candidate['id']);

        // add hero to villains
        $this->es->addToDeck(entityType: 'heroes', entityId: $hero['entity_id'], section: 'villains', special: true);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $hero['entity_id'], section: 'villains');

        // add to heroes
        $this->es->addToDeck(entityType: 'heroes', entityId: $hero['entity_id'], special: true);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $hero['entity_id']);

        // remove candidate
        $this->es->removeCandidate($hero['id']);
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
