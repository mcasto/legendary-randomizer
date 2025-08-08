<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MastermindDisplaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with non-empty data_id
        $users = User::whereNotNull('data_id')->where('data_id', '!=', '')->get();

        foreach ($users as $user) {
            // Check if mastermind display already exists for this user
            $exists = DB::table('mastermind_displays')
                ->where('user_data_id', $user->data_id)
                ->exists();

            if (!$exists) {
                DB::table('mastermind_displays')->insert([
                    'user_data_id' => $user->data_id,
                    'bg' => '#002e5c',
                    'text' => '#ffffff',
                    'order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
