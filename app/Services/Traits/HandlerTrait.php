<?php

namespace App\Services\Traits;

use App\Handlers\HandlerRegistry;
use App\Models\HandlerLog;
use Illuminate\Support\Facades\DB;

trait HandlerTrait
{
    /**
     * Run handler
     */
    protected function runHandler(string $entityType, int $entityId)
    {
        $handler = HandlerRegistry::getHandler(
            entityType: $entityType,
            entityId: $entityId,
            setup: $this->setup
        );

        if (!$handler) {
            return;
        }

        $handler->execute();
        $this->setup->save();
    }

    protected function getHandlerLog(int $setupId)
    {
        return HandlerLog::where('setup_id', $setupId)
            ->whereIn('id', function ($query) use ($setupId) {
                $query->select(DB::raw('MIN(id)'))
                    ->from('handler_logs')
                    ->where('setup_id', $setupId)
                    ->groupBy('entity_type', 'entity_id');
            })
            ->orderBy('id')
            ->get();
    }
}
