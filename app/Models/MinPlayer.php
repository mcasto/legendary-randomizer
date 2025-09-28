<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinPlayer extends Model
{
    protected $fillable = [
        'scheme_id',
        'players'
    ];

    public function scheme(): BelongsTo
    {
        return $this->belongsTo(Scheme::class);
    }
}
