<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntityHandler extends Model
{
    protected $fillable = [
        'entity_type',
        'entity_id',
        'handler_class'
    ];
}
