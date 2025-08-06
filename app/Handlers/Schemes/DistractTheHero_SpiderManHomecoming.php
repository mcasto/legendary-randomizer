<?php

// Distract the Hero
// spiderhomecoming

// Setup : 8 Twists. Use at least 1 Hero.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;
use App\Models\Team;
use App\Services\EntityService;

class DistractTheHero_SpiderManHomecoming extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;

        // select a random hero from that team
        $hero = $this->es->pullCandidate(entityType: 'heroes', team: 'spider-friends', take: 1);

        // add to deck
        $this->es->addToDeck(candidate: $hero);

        // add expectation
        $this->addExpectation(candidate: $hero);

        //remove candidate
        $hero->delete();
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
