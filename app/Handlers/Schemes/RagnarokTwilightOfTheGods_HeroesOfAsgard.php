<?php

// Ragnarok, Twilight of the Gods
// heroesofasgard

// Setup : 11 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class RagnarokTwilightOfTheGods_HeroesOfAsgard extends BaseHandler
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
