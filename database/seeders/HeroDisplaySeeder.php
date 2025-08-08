<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeroDisplaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with non-empty data_id
        $users = User::whereNotNull('data_id')->where('data_id', '!=', '')->get();

        foreach ($users as $user) {
            // Check if hero display already exists for this user
            $exists = DB::table('hero_displays')
                ->where('user_data_id', $user->data_id)
                ->exists();

            if (!$exists) {
                DB::table('hero_displays')->insert([
                    'user_data_id' => $user->data_id,
                    'bg' => '#ffffff',
                    'text' => '#000000',
                    'order' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
