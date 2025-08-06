<?php

// Go After Heroes' Loved Ones
// weaponx

// Setup : Add an extra Hero. Don't use multiple Heroes that have the same Hero Name. 1 player: 8 Twists. 2-4 players: 10 Twists. 5 players: 11 Twists. Set aside a lowest-cost card for each Hero Name, face up, with 2 face up Bystanders under it as "Loved Ones."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;
use App\Services\EntityService;

class GoAfterHeroesLovedOnes_WeaponX extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        switch ($this->setup->players) {
            case 1:
                $this->setup->twists = 8;
                break;

            case 2:
            case 3:
            case 4:
                $this->setup->twists = 10;
                break;

            default:
                $this->setup->twists = 11;
        }

        $this->setup->heroes++;

        $this->setup->bystanders = $this->setup->bystanders + ($this->setup->heroes * 2);

        // get heroes with distinct hero names
        $allHeroes = Hero::select('id', 'name')
            ->groupBy('id', 'name')
            ->inRandomOrder()
            ->take($this->setup->heroes)
            ->get();

        // filter heroes not in candidates
        $heroes = $allHeroes->filter(function ($hero) {
            $candidate = $this->es->getCandidate(entityType: 'heroes', entityId: $hero->id);
            return $candidate;
        });

        foreach ($heroes as $hero) {
            // get candidate
            $candidate = $this->es->getCandidate(entityType: 'heroes', entityId: $hero->id);

            // add to deck
            $this->es->addToDeck(candidate: $candidate);

            // add expectation
            $this->addExpectation(candidate: $candidate);

            // remove candidate
            $candidate->delete();
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
