<?php

// Siphon Energy from the Quantum Realm
// msaw

// Setup : 9 Twists. Set aside the "Quantum Realm" Villain Group as an extra group. Shuffle its Ambush Scheme into the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;
use App\Models\Villain;

class SiphonEnergyFromTheQuantumRealm_MarvelStudiosAntManAndTheWasp extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 9;
        $this->setup->villains++;

        $qr = Villain::where('name', 'Quantum Realm')
            ->first();

        // get candidate
        $candidate = $this->es->getCandidate(entityType: 'villains', entityId: $qr->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to deck
        $this->es->addToDeck(entityType: 'villains', entityId: $qr->id, special: true);

        // add expectation
        $this->addExpectation(entityType: 'villains', entityId: $qr->id);

        // create special
        $special = SpecialEntity::create([
            'name' => 'Shuffle Ambush Scheme into Villain Deck'
        ]);

        // add to deck
        $this->es->addToDeck(entityType: 'special_entities', section: 'villains', entityId: $special->id, special: true);

        // add expectation
        $this->addExpectation(entityType: 'special_entities', section: 'villains', entityId: $special->id);
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
