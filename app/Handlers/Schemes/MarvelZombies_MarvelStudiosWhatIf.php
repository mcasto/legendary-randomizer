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

        // get villain
        $villain_id = array_shift($ids);

        $candidate = $this->es->getCandidate(entityType: 'villains', entityId: $villain_id);

        // remove all villains from candidates with rise of the living dead
        Candidate::where('entity_type', 'villains')
            ->whereIn('entity_id', $ids)
            ->delete();

        // add to deck
        $this->es->addToDeck(candidate: $candidate);

        // add expectation
        $this->addExpectation(candidate: $candidate);

        // get random hero
        $candidate = $this->es->getCandidate(entityType: 'heroes');

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to villains
        $this->es->addToDeck(candidate: $candidate, section: 'villains', special: true);

        // add expectation
        $this->addExpectation(candidate: $candidate, section: 'villains');

        // add to heroes
        $this->es->addToDeck(candidate: $candidate, special: true);

        // add expectation
        $this->addExpectation(candidate: $candidate);
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
