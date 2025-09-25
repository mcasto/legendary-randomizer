<?php

namespace App\Services\Traits;

use App\Models\Deck;
use App\Models\HandlerLog;

trait DeckManagementTrait
{
    /**
     * Is entity in Deck
     */
    public function inDeck(string $entityType, int $entityId, ?string $section = null): bool
    {
        $section = $section ?? $entityType;

        return Deck::where('setup_id', $this->setup->id)
            ->where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->where('section', $section)
            ->exists();
    }

    /**
     * Add to Deck
     * @param object $candidate a candidate pulled from entityService->getCandidate
     * @param string $section which deck does candidate go into
     * @param bool $special is candidate going to be marked as special?
     */
    public function addToDeck($candidate, ?string $section = null, bool $special = false)
    {
        $entityType = $candidate->entity_type;
        $entityId = $candidate->entity_id;

        $section = $section ?? $entityType;

        $deck = Deck::create([
            'setup_id' => $this->setup->id,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'section' => $section,
            'special' => $special,
        ]);

        if (!$deck->entity) {
            logger()->debug([
                'null_entity' => true,
                'setup_id' => $this->setup->id,
                'entity_type' => $entityType,
                'entity_id' => $entityId
            ]);
        }

        if ($entityType != 'schemes' && $entityType != 'masterminds') {
            HandlerLog::create([
                'setup_id' => $this->setup->id,
                'entity_type' => $entityType,
                'entity_id' => $entityId
            ]);
        }

        return $deck->entity;
    }
}
