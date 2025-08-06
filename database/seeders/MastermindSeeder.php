<?php

namespace Database\Seeders;

use App\Models\Mastermind;
use Illuminate\Database\Seeder;

class MastermindSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recs = GetSeedData::pull('masterminds');
        foreach ($recs as $rec) {

            $pattern = '"Always Leads"},": ([^"]+)"';

            preg_match("/$pattern/", json_encode($rec), $m);

            $alwaysLeads = $m[1];

            Mastermind::create([
                'id' => $rec['id'],
                'name' => $rec['name'],
                'set' => $rec['set'],
                'always_leads' => $alwaysLeads
            ]);
        }
    }
}
