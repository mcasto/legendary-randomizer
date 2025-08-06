<?php

namespace App\Handlers;

use App\Models\Candidate;
use App\Models\Expect;
use App\Models\Henchmen;
use App\Models\Setup;
use App\Models\Villain;
use App\Services\EntityService;
use Illuminate\Support\Facades\Auth;

abstract class BaseHandler  // Make it abstract
{
    protected Setup $setup;
    public $es;

    public function __construct(Setup $setup)
    {
        $this->setup = $setup;
        $this->es = new EntityService($setup->id);
    }

    // Make this public so it can be called externally
    public function execute()
    {
        $this->handle();  // This will call the child's implementation
        $this->afterHandle();
    }

    public function addExpectation($candidate, ?string $section = null)
    {
        $entityType = $candidate->entity_type;
        $entityId = $candidate->entity_id;

        $section = $section ?? $entityType;

        Expect::create([
            'setup_id' => $this->setup->id,
            'section' => $section,
            'entity_type' => $entityType,
            'entity_id' => $entityId
        ]);
    }

    public function getAlwaysLeads($candidate)
    {
        // Load the entity if not already loaded
        if (!$candidate->relationLoaded('entity')) {
            $candidate->load('entity');
        }

        $alwaysLeads = $candidate->entity->always_leads;

        $villain = Villain::where('name', $alwaysLeads)
            ->first();

        $henchmen = Henchmen::where('name', $alwaysLeads)
            ->first();

        if ($villain) {
            $entityType = 'villains';
        } else {
            $entityType = 'henchmen';
        }

        return ['entity_type' => $entityType, 'always_leads' => $villain ?? $henchmen];
    }

    public function mastermindWithAlwaysLeads()
    {
        $candidates = Candidate::where('setup_id', $this->setup->id)
            ->where('entity_type', 'masterminds')
            ->with('entity')
            ->inRandomOrder()
            ->get();

        foreach ($candidates as $candidate) {
            $response = $this->getAlwaysLeads($candidate);
            if ($response['always_leads']) {
                return [
                    'candidate' => $candidate,
                    'mastermind' => $candidate->entity,
                    'always_leads' => $response['always_leads'],
                    'entity_type' => $response['entity_type']
                ];
            }
        }
        logger()->error('Unable to find a mastermind who has an always leads');
    }

    // Declare abstract to force children to implement it
    abstract protected function handle();

    protected function afterHandle()
    {
        $this->setup->save();
    }
}
