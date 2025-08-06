<?php

// Divide and Conquer
// champions

// Setup :  8 Twists. 7 Heroes. Sort the Hero Deck by Hero Class: , , , , . (If a card has multiple Classes, break ties at random.) Put these 5 smaller, shuffled Hero Decks beneath the 5 HQ Spaces.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class DivideAndConquer_Champions extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 8;
        $this->setup->heroes = 7;
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
