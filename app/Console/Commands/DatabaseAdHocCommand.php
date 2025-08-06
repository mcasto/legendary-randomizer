<?php

namespace App\Console\Commands;

use App\Models\Candidate;
use App\Models\Deck;
use App\Models\HandlerLog;
use App\Models\Henchmen;
use App\Models\Hero;
use App\Models\HeroTeam;
use App\Models\Mastermind;
use App\Models\Team;
use App\Models\Villain;
use App\Services\EntityService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DatabaseAdHocCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:database-ad-hoc-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private function getTeams($hero)
    {
        // $hero->hero_teams[0]->team_id
        return $hero->hero_teams->map(fn($ht) => $ht->team_id);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $setupId = 2;

        dd(HandlerLog::where('setup_id', $setupId)
            ->whereIn('id', function ($query) use ($setupId) {
                $query->select(DB::raw('MIN(id)'))
                    ->from('handler_logs')
                    ->where('setup_id', $setupId)
                    ->groupBy('entity_type', 'entity_id');
            })
            ->orderBy('id')
            ->get()->toArray());
    }
}
