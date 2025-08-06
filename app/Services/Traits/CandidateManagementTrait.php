<?php

namespace App\Services\Traits;

use App\Models\Candidate;
use App\Models\Scheme;
use App\Models\Mastermind;
use App\Models\Villain;
use App\Models\Henchmen;
use App\Models\Hero;
use App\Models\HasSet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait CandidateManagementTrait
{
    /**
     * Is entity in Candidate
     */
    public function inCandidates(string $entityType, int $entityId): bool
    {
        $section = $section ?? $entityType;

        return Candidate::where('setup_id', $this->setup->id)
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->exists();
    }

    /**
     * Add entities to candidates
     */
    public function addToCandidates($model, $sets, $entityType)
    {
        $query = $model::whereIn('set', $sets);

        // Check if the model's table has the 'unveiled' column
        if (Schema::hasColumn((new $model)->getTable(), 'unveiled')) {
            $query->where('unveiled', 0);
        }


        foreach ($query->get() as $scheme) {
            Candidate::create([
                'setup_id' => $this->setup['id'],
                'entity_type' => $entityType,
                'entity_id' => $scheme->id
            ]);
        }
    }

    /**
     * Get initial candidate entities included in the sets the user has
     */
    protected function getCandidates()
    {
        $sets = HasSet::where('data_id', $this->setup['data_id'])
            ->get()
            ->toArray();

        $sets = array_map(fn($rec) => $rec['set_value'], $sets);

        $this->addToCandidates(Scheme::class, $sets, 'schemes');
        $this->addToCandidates(Mastermind::class, $sets, 'masterminds');
        $this->addToCandidates(Villain::class, $sets, 'villains');
        $this->addToCandidates(Henchmen::class, $sets, 'henchmen');
        $this->addToCandidates(Hero::class, $sets, 'heroes');
    }

    /**
     * Pull candidate
     * @entityType Type of entity (schemes, masterminds, villains, henchmen, heroes)
     * @name Name of entity to pull from candidates (can be regex pattern)
     * @isRegex If $name is regex pattern
     * @take How many matching items to pull (defaults to get all)
     * @keyword Find by keyword
     * @team Find by team
     */
    public function pullCandidate(string $entityType, ?string $name = null, bool $isRegex = false, ?int $take = null, ?string $keyword = null, $team = null)
    {
        $query = Candidate::where('setup_id', $this->setup->id)
            ->where('entity_type', $entityType);

        if ($name) {
            $query->whereHasMorph('entity', [$entityType], function ($query) use ($isRegex, $name) {
                if ($isRegex) {
                    $query->where('name', 'regexp', $name);
                } else {
                    $query->where('name', $name);
                }
            });
        }

        if ($keyword) {
            $query->whereHas('hero_keywords.keyword', function ($query) use ($keyword) {
                $query->where('value', $keyword);
            });
        }

        if ($team) {
            $query->whereHas('hero_teams', function ($query) use ($team) {
                $query->where('value', $team);
            });
        }

        $query->inRandomOrder();

        if ($take == 1) {
            return $query->first();
        }

        if (is_null($take)) {
            return $query->get();
        }

        return $query->take($take)
            ->get();
    }

    /**
     * Get Candidate
     */
    public function getCandidate(string $entityType, $entityId = null, int $take = 1)
    {
        $query = Candidate::where('setup_id', $this->setup->id)
            ->where('entity_type', $entityType);

        if (!is_numeric($entityId ?? null)) {
            $query->inRandomOrder();
        } else {
            $query->where('entity_id', $entityId);
        }

        $query->take($take);

        $result = $take == 1 ? $query->first() : $query->get();

        return $result ? $result : null;
    }

    /**
     * Remove candidate
     */
    public function removeCandidate(int $id): void
    {

        Candidate::find($id)->delete();
    }

    /**
     * Get random heroes from teams that have at least the specified number of candidates
     *
     * @param int $setupId The setup ID to filter candidates
     * @param int $heroesPerTeam Number of heroes to get from each team
     * @param int $numberOfTeams Number of teams to select from
     * @throws \Exception If not enough qualifying teams are found
     */
    public function getHeroesFromQualifiedTeams(
        int $heroesPerTeam,
        int $numberOfTeams = 1
    ): array {
        // Step 1: Get teams with at least $heroesPerTeam heroes in the specified setup
        $teams = DB::table('hero_teams')
            ->select('team_id')
            ->join('candidates', function ($join) {
                $join->on('candidates.entity_id', '=', 'hero_teams.hero_id')
                    ->where('candidates.entity_type', 'heroes')
                    ->where('candidates.setup_id', $this->setup->id);
            })
            ->groupBy('team_id')
            ->havingRaw('COUNT(DISTINCT hero_id) >= ?', [$heroesPerTeam])
            ->inRandomOrder()
            ->limit($numberOfTeams)
            ->pluck('team_id');

        if ($teams->count() < $numberOfTeams) {
            throw new \Exception("Not enough teams with {$heroesPerTeam}+ heroes");
        }

        // Step 2: Get random heroes from each qualifying team
        $selectedCandidates = collect();

        foreach ($teams as $teamId) {
            $heroes = Candidate::where('setup_id', $this->setup->id)
                ->where('entity_type', 'heroes')
                ->whereIn('entity_id', function ($query) use ($teamId) {
                    $query->select('hero_id')
                        ->from('hero_teams')
                        ->where('team_id', $teamId);
                })
                ->with('entity')
                ->inRandomOrder()
                ->limit($heroesPerTeam)
                ->get();

            $selectedCandidates = $selectedCandidates->merge($heroes);
        }

        return $selectedCandidates->toArray();
    }
}
