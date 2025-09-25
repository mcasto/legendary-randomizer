<?php

// Sneak Attack the Heroes' Homes
// annihilation

// Setup : 6 Twists. Each player chooses a Hero to be part of the Hero Deck. Randomly select other Heroes up to the normal number of Heroes. Each player adds to their starting deck three non-rare cards with different names from the Hero they chose and three Wounds.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\SpecialEntity;

class SneakAttackTheHeroesHomes_Annihilation extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 6;

        // create specials
        for ($i = 0; $i < $this->setup->players; $i++) {
            $playerNum = $i + 1;

            $special = SpecialEntity::create([
                'name' => "Player #$playerNum selected Hero"
            ]);

            $candidate = (object)['entity_type' => 'special_entities', 'entity_id' => $special->id];

            // add to deck
            $this->es->addToDeck(candidate: $candidate, section: 'heroes', special: true);

            // add expectation
            $this->addExpectation(candidate: $candidate, section: 'heroes');
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
