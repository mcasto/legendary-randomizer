<?php

// Clash of the Monsters Unleashed
// champions

// Setup : 10 Twists. 6 Wounds per player in the Wound Stack. Shuffle 8 Monsters Unleashed Villains into a face-down "Monster Pit" deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Villain;
use App\Services\EntityService;

class ClashOfTheMonstersUnleashed_Champions extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 10;
        $this->setup->wounds = 6 * $this->setup->players;
        $this->setup->villains++;

        // get monsters
        $monsters = $this->es->pullCandidate(entityType: 'villains', name: 'Monsters Unleashed', take: 1);

        // add monsters to deck
        $this->es->addToDeck(candidate: $monsters, section: 'villains', special: true);

        // add expectation
        $this->addExpectation(candidate: $monsters);

        // remove from candidates
        $monsters->delete();
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
