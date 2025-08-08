<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchemeDisplaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users with non-empty data_id
        $users = User::whereNotNull('data_id')->where('data_id', '!=', '')->get();

        foreach ($users as $user) {
            // Check if scheme display already exists for this user
            $exists = DB::table('scheme_displays')
                ->where('user_data_id', $user->data_id)
                ->exists();

            if (!$exists) {
                DB::table('scheme_displays')->insert([
                    'user_data_id' => $user->data_id,
                    'bg' => '#4c4c4c',
                    'text' => '#ffffff',
                    'order' => 4,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
