<?php

namespace Database\Seeders;

use App\Models\HasSet;
use App\Models\Set;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HasSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(1);

        $data_id = $user->data_id;

        $sets = [13, 3, 19, 34, 41, 16, 11, 22, 24, 12, 28, 32, 26, 10, 5, 14, 15, 17, 35, 27, 21, 31, 25, 38, 33, 39, 20, 1, 36, 29, 30, 4, 7, 23, 18, 40, 37,];

        foreach ($sets as $set_id) {
            $setRec = Set::find($set_id)->toArray();

            $rec = [
                'data_id' => $data_id,
                'set_value' => $setRec['value']
            ];

            HasSet::create($rec);
        }

        $user = User::find(3);
        $sets = Set::all();
        foreach ($sets as $set) {
            $rec = [
                'data_id' => $user->data_id,
                'set_value' => $set->value
            ];

            HasSet::create($rec);
        }
    }
}
