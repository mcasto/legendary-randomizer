<?php

namespace App\Services;

use App\Models\Deck;
use App\Services\Traits\CandidateManagementTrait;
use App\Services\Traits\DeckManagementTrait;
use App\Services\Traits\HandlerTrait;
use App\Models\DefaultSetup;
use App\Models\Setup;

class EntityService
{
    use CandidateManagementTrait, DeckManagementTrait, HandlerTrait;

    public $setup, $mastermind, $scheme;
    public $nestedSchemeCall = false;
    public $veiledSchemeTwists = null;

    public function __construct(
        ?int $setup_id = null,
        ?int $numPlayers = null,
        ?string $dataID = null,
    ) {

        if (!$setup_id) {
            $setup = DefaultSetup::where('players', $numPlayers)
                ->first()
                ->toArray();

            unset($setup['id']);
            $setup['data_id'] = $dataID;

            $this->setup = Setup::create($setup);
            $this->getCandidates();
        } else {
            $this->setup = Setup::find($setup_id);
        }
    }

    public function getScheme($id = null, bool $runHandler = true): void
    {
        // get specified scheme
        $scheme = $this->getCandidate(
            entityType: 'schemes',
            entityId: $id
        );

        // add to deck
        $this->addToDeck(
            $scheme
        );

        // remove candidate
        $scheme->delete();

        if ($runHandler) {
            $this->runHandler('schemes', $scheme['entity_id']);
        }
    }

    public function getMastermind($id = null, bool $runHandler = true): void
    {
        $mastermind = $this->getCandidate(
            entityType: 'masterminds',
            entityId: $id
        );

        // add to deck
        $this->addToDeck(
            $mastermind
        );

        // remove candidate
        $mastermind->delete();

        if ($runHandler) {
            $this->runHandler('masterminds', $mastermind['entity_id']);
        }
    }

    public function fillEntities(string $entityType): void
    {
        $numEntities = Deck::where('setup_id', $this->setup->id)
            ->where('section', $entityType)
            ->count();

        $pullEntities = $this->setup->{$entityType} - $numEntities;

        if ($pullEntities <= 0) {
            return;
        }

        $entities = $this->getCandidate(entityType: $entityType, take: $pullEntities);

        if ($pullEntities == 1) {
            $entity = $entities;
            $addedToDeck = $this->addToDeck(
                $entity
            );

            // remove candidate
            $entity->delete();
        } else {
            foreach ($entities as $entity) {
                $addedToDeck = $this->addToDeck(
                    $entity
                );

                // remove candidate
                $entity->delete();
            }
        }
    }

    public function runHandlerLog()
    {
        $handlerLog = $this->getHandlerLog($this->setup->id);
        foreach ($handlerLog as $entry) {
            $this->runHandler(entityType: $entry->entity_type, entityId: $entry->entity_id);
        }
    }
}
