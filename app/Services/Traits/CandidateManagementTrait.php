<?php

namespace App\Services\Traits;

use App\Models\Candidate;
use App\Models\Scheme;
use App\Models\Mastermind;
use App\Models\Villain;
use App\Models\Henchmen;
use App\Models\Hero;
use App\Models\HasSet;
use App\Models\PlayedCount;
use App\Models\UserSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait CandidateManagementTrait
{
    /**
     * Get user settings for the current data_id
     */
    private function getUserSettings(): ?UserSettings
    {
        return UserSettings::where('user_data_id', $this->setup['data_id'])->first();
    }

    /**
     * Get played counts for entities, grouped by entity_type and entity_id
     */
    private function getPlayedCounts(string $entityType): array
    {
        $counts = PlayedCount::where('data_id', $this->setup['data_id'])
            ->where('entity_type', $entityType)
            ->select('entity_id', DB::raw('COUNT(*) as count'))
            ->groupBy('entity_id')
            ->pluck('count', 'entity_id')
            ->toArray();

        return $counts;
    }

    /**
     * Filter out epic masterminds if use_epics is disabled
     */
    private function filterEpicMasterminds($query, UserSettings $userSettings = null)
    {
        if ($userSettings && !$userSettings->use_epics) {
            $query->whereHasMorph('entity', [Mastermind::class], function ($query) {
                $query->where('name', 'NOT LIKE', '%Epic%');
            });
        }
        return $query;
    }

    /**
     * Apply weighted random ordering based on played counts
     */
    private function applyWeightedRandomOrder($candidates, string $entityType)
    {
        $userSettings = $this->getUserSettings();

        if (!$userSettings || !$userSettings->use_played_count) {
            return $candidates->shuffle();
        }

        $playedCounts = $this->getPlayedCounts($entityType);

        // If no played counts exist, use regular random order
        if (empty($playedCounts)) {
            return $candidates->shuffle();
        }

        // Create a weighted random ordering
        // Lower played counts get higher weights (inverse relationship)
        $maxCount = max($playedCounts) + 1; // Add 1 to avoid zero weights

        // Calculate weights and sort
        $weightedCandidates = $candidates->map(function ($candidate) use ($playedCounts, $maxCount) {
            $playCount = $playedCounts[$candidate->entity_id] ?? 0;
            // Inverse weight: less played = higher weight
            $weight = $maxCount - $playCount;
            $candidate->weight = $weight;
            return $candidate;
        });

        // Sort by weighted random (using weight as probability multiplier)
        $weightedCandidates = $weightedCandidates->shuffle()->sortByDesc(function ($candidate) {
            // Generate random number weighted by play count
            // Less played items get better random scores
            return rand(1, 100) * $candidate->weight;
        });

        return $weightedCandidates;
    }

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
        $userSettings = $this->getUserSettings();

        $query = $model::whereIn('set', $sets);

        // Check if the model's table has the 'unveiled' column
        if (Schema::hasColumn((new $model)->getTable(), 'unveiled')) {
            $query->where('unveiled', 0);
        }

        // Filter out epic masterminds if use_epics is disabled
        if ($entityType === 'masterminds' && $userSettings && !$userSettings->use_epics) {
            $query->where('name', 'NOT LIKE', '%Epic%');
        }

        foreach ($query->get() as $entity) {
            Candidate::create([
                'setup_id' => $this->setup['id'],
                'entity_type' => $entityType,
                'entity_id' => $entity->id
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
     * Get the model class for an entity type
     */
    private function getModelClass(string $entityType): string
    {
        return match ($entityType) {
            'heroes' => Hero::class,
            'masterminds' => Mastermind::class,
            'schemes' => Scheme::class,
            'villains' => Villain::class,
            'henchmen' => Henchmen::class,
            default => throw new \InvalidArgumentException("Unknown entity type: {$entityType}")
        };
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
            $modelClass = $this->getModelClass($entityType);
            $query->whereHasMorph('entity', [$modelClass], function ($query) use ($isRegex, $name) {
                if ($isRegex) {
                    $query->where('name', 'regexp', $name);
                } else {
                    $query->where('name', $name);
                }
            });
        }

        if ($keyword) {
            $query->whereHasMorph('entity', [Hero::class], function ($query) use ($keyword) {
                $query->whereHas('hero_keywords', function ($subQuery) use ($keyword) {
                    $subQuery->whereHas('keyword', function ($keywordQuery) use ($keyword) {
                        $keywordQuery->where('value', $keyword);
                    });
                });
            });
        }

        if ($team) {
            $query->whereHasMorph('entity', [Hero::class], function ($query) use ($team) {
                $query->whereHas('hero_teams', function ($subQuery) use ($team) {
                    $subQuery->whereHas('team', function ($teamQuery) use ($team) {
                        $teamQuery->where('value', $team);
                    });
                });
            });
        }

        $userSettings = $this->getUserSettings();

        // Apply weighted randomization if user has played count weighting enabled
        if ($userSettings && $userSettings->use_played_count) {
            $candidates = $query->get();

            if ($candidates->count() > 0) {
                $candidates = $this->applyWeightedRandomOrder($candidates, $entityType);
            }

            if ($take == 1) {
                return $candidates->first();
            }

            if (is_null($take)) {
                return $candidates;
            }

            return $candidates->take($take);
        } else {
            // Use standard random ordering
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
    }

    /**
     * Get Candidate
     */
    public function getCandidate(string $entityType, $entityId = null, int $take = 1)
    {
        $query = Candidate::where('setup_id', $this->setup->id)
            ->where('entity_type', $entityType);

        if (!is_numeric($entityId ?? null)) {
            $userSettings = $this->getUserSettings();

            // Apply weighted randomization if user has played count weighting enabled
            if ($userSettings && $userSettings->use_played_count) {
                $candidates = $query->get();

                if ($candidates->count() > 0) {
                    $candidates = $this->applyWeightedRandomOrder($candidates, $entityType);
                }

                $result = $take == 1 ? $candidates->first() : $candidates->take($take);
            } else {
                // Use standard random ordering
                $query->inRandomOrder();
                $query->take($take);
                $result = $take == 1 ? $query->first() : $query->get();
            }
        } else {
            $query->where('entity_id', $entityId);
            $query->take($take);
            $result = $take == 1 ? $query->first() : $query->get();
        }

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
