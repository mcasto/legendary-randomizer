<?php

// Fragmented Realities
// sw1

// Setup : Add an extra Villain Group. Shuffle the Villain Deck, then split it as evenly as possible into a Villain Deck for each player. Then, shuffle 2 Twists into each player's Villain Deck.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class FragmentedRealities_SecretWarsVolume1 extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 2 * $this->setup->players;
        $this->setup->villains++;
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
