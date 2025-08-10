<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayedCount extends Model
{
    protected $fillable = [
        'data_id',
        'entity_type',
        'entity_id'
    ];
}
