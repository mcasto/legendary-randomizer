<?php

// Transform Citizens Into Demons
// darkcity

// Setup : 8 Twists. Villain Deck includes 14 extra Jean Grey cards and no Bystanders.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;

class TransformCitizensIntoDemons_DarkCity extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->bystanders = 0;

        // get jean grey
        $jean = Hero::where('name', 'Jean Grey')
            ->first();

        // get candidate
        $candidate = $this->es->getCandidate(entityType: 'heroes', entityId: $jean->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to deck
        $this->es->addToDeck(entityType: 'heroes', entityId: $jean->id, section: 'villains', special: true);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $jean->id, section: 'villains');
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
