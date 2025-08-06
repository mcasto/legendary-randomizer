<?php

// Condition Logan into Weapon X
// weaponx

// Setup : 8 Twists. Include exactly 1 Hero with Wolverine or Logan in its name.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Hero;
use App\Services\EntityService;

class ConditionLoganIntoWeaponX_WeaponX extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;

        // find heroes with wolverine & logan in name
        $heroes = $this->es->pullCandidate(entityType: 'heroes', name: '\b(?:Wolverine|Logan)\b', isRegex: true);

        $hero = $heroes[0];

        // add to deck
        $this->es->addToDeck(candidate: $hero);

        // add expectation
        $this->addExpectation(candidate: $hero);

        // remove candidates
        foreach ($heroes as $hero) {
            $hero->delete();
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
