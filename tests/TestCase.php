<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\HasSet;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalTestData();
    }

    protected function createMinimalTestData(): void
    {
        // Seed DefaultSetup data - required for EntityService to work
        $this->seed(\Database\Seeders\DefaultSetupSeeder::class);

        // Seed related data first to satisfy foreign key constraints
        $this->seed(\Database\Seeders\SetSeeder::class);
        $this->seed(\Database\Seeders\ColorSeeder::class);
        $this->seed(\Database\Seeders\KeywordSeeder::class);
        $this->seed(\Database\Seeders\TeamSeeder::class);

        // Seed all entities for comprehensive testing - this is needed because the
        // EntityService expects to find actual entities in the database to populate Candidates
        $this->seed(\Database\Seeders\SchemeSeeder::class);
        $this->seed(\Database\Seeders\MastermindSeeder::class);
        $this->seed(\Database\Seeders\VillainSeeder::class);
        $this->seed(\Database\Seeders\HenchmenSeeder::class);
        $this->seed(\Database\Seeders\HeroSeeder::class);

        // Seed relationship tables
        $this->seed(\Database\Seeders\HeroColorSeeder::class);
        $this->seed(\Database\Seeders\HeroTeamSeeder::class);

        // Create a test user with ID 3 to match existing test expectations
        User::factory()->create([
            'id' => 3,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'data_id' => 'test-data-id',
        ]);

        // Create HasSet records for the test user so they "own" ALL sets
        // This makes user ID 3 the comprehensive test user with access to every entity
        $allSets = [
            'coreset',
            'promo',
            'darkcity',
            'ff',
            'pttr',
            'villains',
            'gotg',
            'fearitself',
            '3d',
            'sw1',
            'sw2',
            'captainamerica',
            'civilwar',
            'deadpool',
            'noir',
            'xmen',
            'spiderhomecoming',
            'champions',
            'wwhulk',
            'marvelstudios',
            'antman',
            'venom',
            'dimensions',
            'revelations',
            'shield',
            'heroesofasgard',
            'newmutants',
            'intothecosmos',
            'realmofkings',
            'annihilation',
            'messiahcomplex',
            'doctorstrange',
            'msgotg',
            'blackpanther',
            'blackwidow',
            'msis',
            'midnightsons',
            'mswi',
            'msaw',
            '2099',
            'weaponx'
        ];

        foreach ($allSets as $setName) {
            HasSet::create([
                'data_id' => 'test-data-id',
                'set_value' => $setName,
            ]);
        }

        // NOTE: The entity seeders populate the database with all schemes, masterminds,
        // villains, henchmen, and heroes from the production seed data. This allows
        // EntityService to work properly by finding entities to populate the Candidates table.
    }
}
