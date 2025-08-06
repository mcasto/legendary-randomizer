<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SetSeeder::class,
            EntityHandlerSeeder::class,
            HasSetSeeder::class,
            HenchmenSeeder::class,
            ColorSeeder::class,
            TeamSeeder::class,
            KeywordSeeder::class,
            HeroSeeder::class,
            HeroColorSeeder::class,
            HeroTeamSeeder::class,
            MastermindSeeder::class,
            SchemeSeeder::class,
            VillainSeeder::class,
            DefaultSetupSeeder::class,
        ]);
    }
}
