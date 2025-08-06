<?php

// Warp Reality into a TV Show
// msis

// Setup : 11 Twists. The rightmost city space represents a TV show from ”the 50s.” The space on its left is ”the 60s,” then ”the 70s.” The city is only those 3 spaces. The HQ is only the 3 spaces beneath those. Move the Mastermind & Officer Deck to mark the city's left edge.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class WarpRealityIntoATVShow_MarvelStudiosTheInfinitySaga extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=11;
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
