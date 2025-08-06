<?php

// Dark World of Svartalfheim, The
// heroesofasgard

// Setup : 10 Twists.

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class DarkWorldOfSvartalfheimThe_HeroesOfAsgard extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=10;
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
