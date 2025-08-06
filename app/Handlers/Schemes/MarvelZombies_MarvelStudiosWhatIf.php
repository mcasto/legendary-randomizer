<?php

// Marvel Zombies
// mswi

// Setup : 4 Twists. Include exactly one Villain Group with " ." Add 8 random cards from an extra Hero to the Villain Deck. 1-2 players: Add 3 Bystanders.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Candidate;
use App\Models\Hero;
use App\Models\Villain;
use App\Services\EntityService;

class MarvelZombies_MarvelStudiosWhatIf extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 4;
        $this->setup->heroes++;
        $this->setup->villains++;
        if ($this->setup->players < 3) {
            $this->setup->bystanders += 3;
        }

        $ids = Villain::whereHas('villain_keywords.keyword', function ($query) {
            $query->where('value', 'riseofthelivingdead');
        })
            ->inRandomOrder()
            ->get()
            ->map(fn($villain) => $villain->id)
            ->toArray();

        // remove all villains from candidates with rise of the living dead
        Candidate::where('entity_type', 'villains')
            ->whereIn('entity_id', $ids)
            ->delete();

        // get villain
        $villain = array_shift($ids);

        // add to deck
        $this->es->addToDeck(entityType: 'villains', entityId: $villain);

        // add expectation
        $this->addExpectation(entityType: 'villains', entityId: $villain);

        // get random hero
        $candidate = $this->es->getCandidate(entityType: 'heroes');

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to villains
        $this->es->addToDeck(entityType: 'heroes', entityId: $candidate['id'], section: 'villains', special: true);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $candidate['id'], section: 'villains');

        // add to heroes
        $this->es->addToDeck(entityType: 'heroes', entityId: $candidate['id'], special: true);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $candidate['id']);
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
