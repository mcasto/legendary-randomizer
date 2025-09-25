<?php

// Trash Earth with Hugest Party Ever
// mswi

// Setup : 6 Twists. Always include the Party Thor Hero and Intergalactic Party Animals Villain Group.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;
use App\Models\Villain;

class TrashEarthWithHugestPartyEver_MarvelStudiosWhatIf extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 6;

        $thor = Hero::where('name', 'Party Thor')
            ->first();

        $animals = Villain::where('name', 'Intergalactic Party Animals')
            ->first();

        // get thor candidate
        $candidate = $this->es->getCandidate(entityType: 'heroes', entityId: $thor->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // get animals candidate
        $candidate = $this->es->getCandidate(entityType: 'villains', entityId: $animals->id);

        // add thor to deck
        $this->es->addToDeck(candidate: $thor);

        // add expectation
        $this->addExpectation(candidate: $thor);

        // add animals to deck
        $this->es->addToDeck(candidate: $animals);

        // add expectation
        $this->addExpectation(candidate: $animals);
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
