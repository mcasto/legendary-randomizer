<?php

// ...Reveal The Heroes' Evil Clones
// messiahcomplex

// 

namespace App\Handlers\Schemes;

use App\Handlers\BaseHandler;

class RevealTheHeroesEvilClones_MessiahComplex extends BaseHandler
{
    /**
     * Handle Schemes operations.
     */
    protected function handle():void
    {
        $this->setup->twists=-1;
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
