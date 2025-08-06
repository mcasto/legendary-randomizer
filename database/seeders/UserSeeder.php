<?php

namespace Database\Seeders;

use App\Models\HasSet;
use App\Models\Set;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = json_decode(env('DEFAULT_USERS'), true);

        $data_id = uniqid();

        foreach ($users as $user) {
            $user['data_id'] = $data_id;
            $user['email_verified_at'] = now();
            User::create($user);
        }

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'data_id' => uniqid()
        ]);
    }
}
