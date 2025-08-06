<?php

// Auction Shrink Tech to Highest Bidder
// msaw

// Setup : 11 Twists. Set aside all 14 cards of a random extra Hero that has any cards as "Shrink Tech."

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;
use App\Models\Candidate;
use App\Models\Deck;
use App\Models\Hero;

class AuctionShrinkTechToHighestBidder_MarvelStudiosAntManAndTheWasp extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle(): void
    {
        $this->setup->twists = 11;
        $this->setup->heroes++;
        $this->setup->villains++;

        // get sizechanging hero
        $sizer = $this->es->pullCandidate(entityType: 'heroes', keyword: 'sizechanging', take: 1);

        // If no sizechanging hero found, return early
        if (!$sizer) {
            return;
        }

        // add to heroes
        $this->es->addToDeck($sizer, special: true);

        // add to villains
        $this->es->addToDeck($sizer, 'villains', true);

        // add expectations
        $this->addExpectation($sizer);
        $this->addExpectation($sizer, 'villains');

        // remove candidate
        $sizer->delete();
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
