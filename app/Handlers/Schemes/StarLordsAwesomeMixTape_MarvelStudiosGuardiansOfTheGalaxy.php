<?php

// Star-Lord's Awesome Mix Tape
// msgotg

// Setup : 7 Twists. Use 7 Heroes including at least one Hero. Use double the normal number of Villain and Henchman Groups, but use only half the cards from each of those groups, randomly & secretly. (1 player: 2 Henchmen per group)

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;
use App\Models\Team;

class StarLordsAwesomeMixTape_MarvelStudiosGuardiansOfTheGalaxy extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 7;
        $this->setup->heroes = 7;
        $this->setup->villains *= 2;
        $this->setup->henchmen *= 2;

        // get guardian hero
        $hero = Hero::whereHas('hero_teams.team', function ($query) {
            $query->where('value', 'guardians-of-the-galaxy');
        })
            ->inRandomOrder()
            ->first();

        // get candidate
        $candidate = $this->es->getCandidate(entityType: 'heroes', entityId: $hero->id);

        // remove candidate
        $this->es->removeCandidate($candidate['id']);

        // add to deck
        $this->es->addToDeck(entityType: 'heroes', entityId: $hero->id);

        // add expectation
        $this->addExpectation(entityType: 'heroes', entityId: $hero->id);
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
