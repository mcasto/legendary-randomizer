<?php

// Secret Invasion of the Skrull Shapeshifters
// coreset

// Setup : 8 Twists. 6 Heroes. Skrull Villain Group required. Shuffle 12 random Heroes from the Hero Deck into the Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;
use App\Models\Villain;

class SecretInvasionOfTheSkrullShapeshifters_CoreSet extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->heroes = 6;

        if ($this->setup->villains < 2) {
            $this->setup->villains = 2;
        }

        // get skrulls
        $skrulls = Villain::where('name', 'Skrulls')
            ->first();

        // get candidate
        $candidate = $this->es->getCandidate(entityType: 'villains', entityId: $skrulls->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to deck
        $this->es->addToDeck(entityType: 'villains', entityId: $skrulls->id);

        // add expectation
        $this->addExpectation(entityType: 'villains', entityId: $skrulls->id);

        // create special entity
        $special = SpecialEntity::create([
            'name' => '12 Random Heroes'
        ]);

        // add to deck
        $this->es->addToDeck(entityType: 'special_entities', entityId: $special->id, section: 'villains', special: true);

        // add expectation
        $this->addExpectation(entityType: 'special_entities', entityId: $special->id, section: 'villains');
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
