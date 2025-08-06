<?php

namespace App\Providers;

use App\Models\Henchmen;
use App\Models\Hero;
use App\Models\Mastermind;
use App\Models\Scheme;
use App\Models\SpecialEntity;
use App\Models\Villain;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'schemes' => Scheme::class,
            'masterminds' => Mastermind::class,
            'villains' => Villain::class,
            'henchmen' => Henchmen::class,
            'heroes' => Hero::class,
            'special_entities' => SpecialEntity::class
        ]);
    }
}
