<?php

// The Time Heist
// msis

// Setup : 11 Twists. Use 4 Heroes in the Hero Deck, plus 4 other Heroes to make a ”Past Hero Deck.” Above the board, make room for an alternate city called ”The Past.” It has the normal 5 spaces, from Sewers to Bridge. The Past has its own ”Past HQ” filled by the ”Past Hero Deck.” To start, play as if ”The Past” city, HQ, and Hero Deck don't exist.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class TheTimeHeist_MarvelStudiosTheInfinitySaga extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;

        $this->setup->heroes = 8;

        // get 4 special "past" heroes
        $heroes = $this->es->getCandidate(entityType: 'heroes', take: 4);

        foreach ($heroes as $hero) {
            // remove candidate
            $this->es->removeCandidate($hero['id']);

            // add to deck
            $this->es->addToDeck(candidate: $hero, special: true);

            // add expectation
            $this->addExpectation(candidate: $hero);
        }
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
