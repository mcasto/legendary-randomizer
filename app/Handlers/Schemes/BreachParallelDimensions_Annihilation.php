<?php

// Breach Parallel Dimensions
// annihilation

// Setup : 6 Twists. Add 4 extra Bystanders to the Villain Deck. Deal the shuffled Villain Deck into several "Dimension" decks where the first Dimension has 1 card, the next has 2 cards, then 3, 4, etc. (The final Dimension might not have enough cards to reach its full number.)

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class BreachParallelDimensions_Annihilation extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 6;
        $this->setup->bystanders += 4;
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
