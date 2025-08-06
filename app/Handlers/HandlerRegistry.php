<?php

namespace App\Handlers;

use App\Handlers\Contracts\EntityHandlerInterface;
use App\Models\EntityHandler;
use App\Models\Setup;

class HandlerRegistry
{
    public static function getHandler(string $entityType, int $entityId, Setup $setup)
    {
        $handler = EntityHandler::where('entity_type', $entityType)
            ->where('entity_id', $entityId)
            ->first();

        if ($handler && class_exists($handler->handler_class)) {
            return app($handler->handler_class, ['setup' => $setup]);
        }

        return null;
    }
}
