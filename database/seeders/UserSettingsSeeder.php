<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with non-empty data_id
        $users = User::whereNotNull('data_id')->where('data_id', '!=', '')->get();

        foreach ($users as $user) {
            // Check if user settings already exist for this user
            $exists = DB::table('user_settings')
                ->where('user_data_id', $user->data_id)
                ->exists();

            if (!$exists) {
                DB::table('user_settings')->insert([
                    'user_data_id' => $user->data_id,
                    'use_played_count' => true,
                    'use_epics' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
